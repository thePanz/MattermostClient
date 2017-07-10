<?php

declare(strict_types=1);

namespace Pnz\MattermostClient;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Message\RequestFactory;
use Pnz\MattermostClient\Api\ChannelsApi;
use Pnz\MattermostClient\Api\FilesApi;
use Pnz\MattermostClient\Api\PostsApi;
use Pnz\MattermostClient\Api\TeamsApi;
use Pnz\MattermostClient\Api\UsersApi;
use Pnz\MattermostClient\Hydrator\Hydrator;
use Pnz\MattermostClient\Hydrator\ModelHydrator;

final class ApiClient
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var Hydrator
     */
    private $hydrator;

    /**
     * @var RequestFactory
     */
    private $messageFactory;

    /**
     * Construct an ApiClient instance.
     *
     * @param HttpClient|null     $httpClient     The HttpClient;  if null, the auto-discover will be used
     * @param MessageFactory|null $requestFactory The MessageFactory; if null, the auto-discover will be used
     * @param Hydrator|null       $hydrator       The Hydrator to use, default to the ModelHydrator
     */
    public function __construct(
        HttpClient $httpClient = null,
        MessageFactory $requestFactory = null,
        Hydrator $hydrator = null
    ) {
        $this->httpClient = $httpClient ?: HttpClientDiscovery::find();
        $this->messageFactory = $requestFactory ?: MessageFactoryDiscovery::find();
        $this->hydrator = $hydrator ?: new ModelHydrator();
    }

    /**
     * Returns configured ApiClient from the given Configurator, Hydrator and RequestFactory.
     *
     * @param HttpClientConfigurator $httpClientConfigurator
     * @param Hydrator|null          $hydrator
     * @param RequestFactory|null    $requestFactory
     *
     * @return ApiClient
     */
    public static function configure(
        HttpClientConfigurator $httpClientConfigurator,
        RequestFactory $requestFactory = null,
        Hydrator $hydrator = null
    ): self {
        $httpClient = $httpClientConfigurator->createConfiguredClient();

        return new self($httpClient, $requestFactory, $hydrator);
    }

    /**
     * Return a client handling the Users resources.
     *
     * @return Api\UsersApi
     */
    public function users(): UsersApi
    {
        return new Api\UsersApi($this->httpClient, $this->messageFactory, $this->hydrator);
    }

    /**
     * Return a client handling the Teams resources.
     *
     * @return Api\TeamsApi
     */
    public function teams(): TeamsApi
    {
        return new Api\TeamsApi($this->httpClient, $this->messageFactory, $this->hydrator);
    }

    /**
     * Return a client handling the Channels resources.
     *
     * @return Api\ChannelsApi
     */
    public function channels(): ChannelsApi
    {
        return new Api\ChannelsApi($this->httpClient, $this->messageFactory, $this->hydrator);
    }

    /**
     * Return a client handling the Posts resources.
     *
     * @return Api\PostsApi
     */
    public function posts(): PostsApi
    {
        return new Api\PostsApi($this->httpClient, $this->messageFactory, $this->hydrator);
    }

    /**
     * Return a client handling the Files resources.
     *
     * @return Api\FilesApi
     */
    public function files(): FilesApi
    {
        return new Api\FilesApi($this->httpClient, $this->messageFactory, $this->hydrator);
    }
}
