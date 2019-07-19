<?php

declare(strict_types=1);

namespace Pnz\MattermostClient;

use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\Authentication;
use Pnz\MattermostClient\Authentication\MattermostAuthentication;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

/**
 * Configure an HTTP client.
 *
 * @internal this class should not be used outside of the API Client, it is not part of the BC promise
 */
final class HttpClientConfigurator
{
    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $loginId;

    /**
     * @var string
     */
    private $password;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var UriFactoryInterface
     */
    private $uriFactory;

    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * @var Plugin[]
     */
    private $prependPlugins = [];

    /**
     * @var Plugin[]
     */
    private $appendPlugins = [];

    public function __construct(
        HttpClient $httpClient = null,
        UriFactoryInterface $uriFactory = null,
        RequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface $streamFactory = null
    ) {
        $this->httpClient = $httpClient ?? HttpClientDiscovery::find();
        $this->uriFactory = $uriFactory ?? Psr17FactoryDiscovery::findUrlFactory();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
    }

    public function createConfiguredClient(): HttpClient
    {
        if (empty($this->endpoint)) {
            throw new \InvalidArgumentException('Unable to configure the client, no API Endpoint provided');
        }

        if (empty($this->loginId) || empty($this->password)) {
            throw new \InvalidArgumentException('Unable to configure the client, no LoginId or Password provided');
        }

        $baseUri = $this->uriFactory->createUri($this->endpoint);
        $plugins = $this->prependPlugins;

        $plugins[] = new Plugin\BaseUriPlugin($baseUri);
        $plugins[] = new Plugin\AuthenticationPlugin($this->createAuthentication($baseUri));

        return new PluginClient($this->httpClient, array_merge($plugins, $this->appendPlugins));
    }

    /**
     * @return HttpClientConfigurator
     */
    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Set the LoginId/password to be used during the authentication.
     *
     * @return HttpClientConfigurator
     */
    public function setCredentials(string $loginId, string $password): self
    {
        if (empty($loginId) || empty($password)) {
            throw new \InvalidArgumentException('LoginId and Password cannot be empty');
        }

        $this->loginId = $loginId;
        $this->password = $password;

        return $this;
    }

    public function appendPlugin(Plugin ...$plugin): self
    {
        foreach ($plugin as $p) {
            $this->appendPlugins[] = $p;
        }

        return $this;
    }

    public function prependPlugin(Plugin ...$plugin): self
    {
        $plugin = array_reverse($plugin);
        foreach ($plugin as $p) {
            array_unshift($this->prependPlugins, $p);
        }

        return $this;
    }

    private function createAuthentication(UriInterface $baseUri): MattermostAuthentication
    {
        $authClient = new PluginClient($this->httpClient, array_merge($this->prependPlugins, [
            new Plugin\BaseUriPlugin($baseUri),
        ]));

        return new MattermostAuthentication($this->loginId, $this->password, $authClient, $this->requestFactory, $this->streamFactory);
    }
}
