<?php

use Http\Mock\Client;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\HttpClientConfigurator;

/**
 * @internal
 */
final class HttpClientConfiguratorTest extends TestCase
{
    private const ENDPOINT = 'https://example.com:8080/api/v4';
    protected const LOGIN_USERNAME = 'UserName';
    protected const LOGIN_PASSWORD = '90ef6f5c4b08aabc';
    protected const LOGIN_TOKEN = '489c7169-a23e-4943-8f59-2f2a3d0f2b12';

    private Psr17Factory $psr17Factory;
    private Client $httpClient;
    private HttpClientConfigurator $configurator;

    protected function setUp(): void
    {
        $this->psr17Factory = new Psr17Factory();
        $this->httpClient = new Client($this->psr17Factory);

        $this->configurator = new HttpClientConfigurator(
            $this->httpClient,
            $this->psr17Factory,
            $this->psr17Factory,
            $this->psr17Factory,
        );
    }

    public function testCreateWithToken(): void
    {
        $this->configurator->setEndpoint(self::ENDPOINT)
            ->setToken(self::LOGIN_TOKEN)
        ;

        $client = $this->configurator->createConfiguredClient();

        $client->sendRequest($this->psr17Factory->createRequest('GET', '/test'));

        $requests = $this->httpClient->getRequests();

        $this->assertCount(1, $requests);
        $this->assertArrayHasKey('Authorization', $requests[0]->getHeaders());
        $this->assertSame('Bearer '.self::LOGIN_TOKEN, $requests[0]->getHeaderLine('Authorization'));
    }
}
