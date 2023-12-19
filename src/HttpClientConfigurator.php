<?php

declare(strict_types=1);

namespace Pnz\MattermostClient;

use Http\Client\Common\Plugin;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Http\Message\Authentication\Bearer;
use Pnz\MattermostClient\Authentication\MattermostAuthentication;
use Psr\Http\Client\ClientInterface;
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
    private ?string $endpoint = null;
    private ?string $loginId = null;
    private ?string $password = null;
    private ?string $token = null;

    private readonly ClientInterface $httpClient;
    private readonly UriFactoryInterface $uriFactory;
    private readonly RequestFactoryInterface $requestFactory;
    private readonly StreamFactoryInterface $streamFactory;

    /**
     * @var list<Plugin>
     */
    private array $prependPlugins = [];

    /**
     * @var list<Plugin>
     */
    private array $appendPlugins = [];

    public function __construct(
        ClientInterface $httpClient = null,
        UriFactoryInterface $uriFactory = null,
        RequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface $streamFactory = null
    ) {
        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
        $this->uriFactory = $uriFactory ?? Psr17FactoryDiscovery::findUriFactory();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
    }

    public function createConfiguredClient(): ClientInterface
    {
        if (empty($this->endpoint)) {
            throw new \InvalidArgumentException('Unable to configure the client, no API Endpoint provided');
        }

        if (!$this->token || (!$this->loginId && !$this->password)) {
            throw new \InvalidArgumentException('Unable to configure the client, no LoginId/Password or Token provided');
        }

        $baseUri = $this->uriFactory->createUri($this->endpoint);
        $plugins = $this->prependPlugins;

        $plugins[] = new BaseUriPlugin($baseUri);
        if ($this->loginId && $this->password) {
            $plugins[] = new AuthenticationPlugin($this->createAuthentication($baseUri, $this->loginId, $this->password));
        } else {
            $plugins[] = new AuthenticationPlugin(new Bearer($this->token));
        }

        return new PluginClient($this->httpClient, array_merge($plugins, $this->appendPlugins));
    }

    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Set the LoginId/password to be used during the authentication.
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

    /**
     * Set the Mattermost token to be used during the authentication.
     */
    public function setToken(string $token): self
    {
        if (empty($token)) {
            throw new \InvalidArgumentException('Token cannot be empty');
        }

        $this->token = $token;

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

    private function createAuthentication(UriInterface $baseUri, string $loginId, string $password): MattermostAuthentication
    {
        $authClient = new PluginClient($this->httpClient, [...$this->prependPlugins, new BaseUriPlugin($baseUri)]);

        return new MattermostAuthentication($loginId, $password, $authClient, $this->requestFactory, $this->streamFactory);
    }
}
