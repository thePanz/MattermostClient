<?php

declare(strict_types=1);

namespace Pnz\MattermostClient;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Pnz\MattermostClient\Api\ChannelsApi;
use Pnz\MattermostClient\Api\FilesApi;
use Pnz\MattermostClient\Api\PostsApi;
use Pnz\MattermostClient\Api\TeamsApi;
use Pnz\MattermostClient\Api\UsersApi;
use Pnz\MattermostClient\Contract\HydratorInterface;
use Pnz\MattermostClient\Hydrator\ModelHydrator;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class ApiClient
{
    private readonly ClientInterface $httpClient;
    private readonly HydratorInterface $hydrator;
    private readonly RequestFactoryInterface $requestFactory;
    private readonly StreamFactoryInterface $streamFactory;

    /**
     * Construct an ApiClient instance.
     *
     * @param ClientInterface|null         $httpClient     The HttpClient;  if null, the auto-discover will be used
     * @param RequestFactoryInterface|null $requestFactory The RequestFactory; if null, the auto-discover will be used
     */
    public function __construct(
        ClientInterface $httpClient = null,
        RequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface $streamFactory = null,
    ) {
        $this->httpClient = $httpClient ?: Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?: Psr17FactoryDiscovery::findStreamFactory();
        $this->hydrator = new ModelHydrator();
    }

    /**
     * Returns configured ApiClient from the given Configurator and RequestFactory.
     */
    public static function configure(
        HttpClientConfigurator $httpClientConfigurator,
        RequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface $streamFactory = null,
    ): self {
        $httpClient = $httpClientConfigurator->createConfiguredClient();

        return new self($httpClient, $requestFactory, $streamFactory);
    }

    /**
     * Return a client handling the Users resources.
     */
    public function users(): UsersApi
    {
        return new UsersApi($this->httpClient, $this->requestFactory, $this->streamFactory, $this->hydrator);
    }

    /**
     * Return a client handling the Teams resources.
     */
    public function teams(): TeamsApi
    {
        return new TeamsApi($this->httpClient, $this->requestFactory, $this->streamFactory, $this->hydrator);
    }

    /**
     * Return a client handling the Channels resources.
     */
    public function channels(): ChannelsApi
    {
        return new ChannelsApi($this->httpClient, $this->requestFactory, $this->streamFactory, $this->hydrator);
    }

    /**
     * Return a client handling the Posts resources.
     */
    public function posts(): PostsApi
    {
        return new PostsApi($this->httpClient, $this->requestFactory, $this->streamFactory, $this->hydrator);
    }

    /**
     * Return a client handling the Files resources.
     */
    public function files(): FilesApi
    {
        return new FilesApi($this->httpClient, $this->requestFactory, $this->streamFactory, $this->hydrator);
    }
}
