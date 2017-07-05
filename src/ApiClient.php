<?php

declare(strict_types=1);

namespace Pnz\MattermostClient;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Message\RequestFactory;
use Pnz\MattermostClient\Api\Channels;
use Pnz\MattermostClient\Api\Files;
use Pnz\MattermostClient\Api\Posts;
use Pnz\MattermostClient\Api\Teams;
use Pnz\MattermostClient\Api\Users;
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
     * @return Api\Users
     */
    public function users(): Users
    {
        return new Api\Users($this->httpClient, $this->messageFactory, $this->hydrator);
    }

    /**
     * Return a client handling the Teams resources.
     *
     * @return Api\Teams
     */
    public function teams(): Teams
    {
        return new Api\Teams($this->httpClient, $this->messageFactory, $this->hydrator);
    }

    /**
     * Return a client handling the Channels resources.
     *
     * @return Api\Channels
     */
    public function channels(): Channels
    {
        return new Api\Channels($this->httpClient, $this->messageFactory, $this->hydrator);
    }

    /**
     * Return a client handling the Posts resources.
     *
     * @return Api\Posts
     */
    public function posts(): Posts
    {
        return new Api\Posts($this->httpClient, $this->messageFactory, $this->hydrator);
    }


    /**
     * Return a client handling the Files resources.
     *
     * @return Api\Files
     */
    public function files(): Files
    {
        return new Api\Files($this->httpClient, $this->messageFactory, $this->hydrator);
    }
}
