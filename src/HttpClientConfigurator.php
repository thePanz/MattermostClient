<?php

declare(strict_types=1);

namespace Pnz\MattermostClient;

use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Http\Message\Authentication;
use Http\Message\RequestFactory;
use Http\Message\UriFactory;
use Pnz\MattermostClient\Plugin\MattermostLoginPlugin;

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
    private $endpoint = 'https://fake-twitter.com';

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
     * @var UriFactory
     */
    private $uriFactory;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var Plugin[]
     */
    private $prependPlugins = [];

    /**
     * @var Plugin[]
     */
    private $appendPlugins = [];

    /**
     * @param HttpClient|null     $httpClient
     * @param UriFactory|null     $uriFactory
     * @param RequestFactory|null $requestFactory
     */
    public function __construct(HttpClient $httpClient = null, UriFactory $uriFactory = null, RequestFactory $requestFactory = null)
    {
        $this->httpClient = $httpClient ?? HttpClientDiscovery::find();
        $this->uriFactory = $uriFactory ?? UriFactoryDiscovery::find();
        $this->requestFactory = $requestFactory ?? MessageFactoryDiscovery::find();
    }

    /**
     * @return HttpClient
     */
    public function createConfiguredClient(): HttpClient
    {
        $plugins = $this->prependPlugins;
        $plugins[] = new Plugin\BaseUriPlugin($this->uriFactory->createUri($this->endpoint));
        $plugins[] = new Plugin\HeaderDefaultsPlugin([
            'User-Agent' => 'thePanz/MattermostClient (https://github.com/thePanz/MattermostClient)',
        ]);

        if (null !== $this->token) {
            $plugins[] = new Plugin\AuthenticationPlugin(new Authentication\Bearer($this->token));
        } elseif (!empty($this->loginId) && (!empty($this->password))) {
            $plugins[] = new MattermostLoginPlugin($this->loginId, $this->password, $this->requestFactory);
        } else {
            throw new \InvalidArgumentException('Unable to configure the client, no Token or LoginId/Password provided');
        }

        return new PluginClient($this->httpClient, array_merge($plugins, $this->appendPlugins));
    }

    /**
     * @param string $endpoint
     *
     * @return HttpClientConfigurator
     */
    public function setEndpoint(string $endpoint): HttpClientConfigurator
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Configure the authentication token, it will skip the login_id/password auto-authentication.
     *
     * @param string $token
     *
     * @return HttpClientConfigurator
     */
    public function setToken(string $token): HttpClientConfigurator
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Set the LoginId/password to be used during the authentication.
     *
     * @param string $loginId
     * @param string $password
     *
     * @return HttpClientConfigurator
     */
    public function setCredentials(string $loginId, string $password): HttpClientConfigurator
    {
        if (empty($loginId) || empty($password)) {
            throw new \InvalidArgumentException('LoginId and Password cannot be empty');
        }

        $this->loginId = $loginId;
        $this->password = $password;

        return $this;
    }

    /**
     * @param Plugin|Plugin[] ...$plugin
     *
     * @return HttpClientConfigurator
     */
    public function appendPlugin(Plugin ...$plugin): HttpClientConfigurator
    {
        foreach ($plugin as $p) {
            $this->appendPlugins[] = $p;
        }

        return $this;
    }

    /**
     * @param Plugin|Plugin[] ...$plugin
     *
     * @return HttpClientConfigurator
     */
    public function prependPlugin(Plugin ...$plugin): HttpClientConfigurator
    {
        $plugin = array_reverse($plugin);
        foreach ($plugin as $p) {
            array_unshift($this->prependPlugins, $p);
        }

        return $this;
    }
}
