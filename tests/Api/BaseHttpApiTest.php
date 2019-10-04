<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Api;

use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Exception\ApiException;
use Pnz\MattermostClient\Exception\Domain\DisabledFeatureException;
use Pnz\MattermostClient\Exception\Domain\MissingAccessTokenException;
use Pnz\MattermostClient\Exception\Domain\NotFoundException;
use Pnz\MattermostClient\Exception\Domain\PermissionDeniedException;
use Pnz\MattermostClient\Exception\Domain\ValidationException;
use Pnz\MattermostClient\Hydrator\Hydrator;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class BaseHttpApiTest extends TestCase
{
    /** @var HttpClient|MockObject */
    protected $httpClient;

    /** @var ResponseInterface|MockObject */
    protected $response;

    /** @var RequestInterface|MockObject */
    protected $request;

    /** @var Hydrator|MockObject */
    protected $hydrator;

    /** @var RequestFactory|MockObject */
    protected $requestFactory;

    protected function setUp(): void
    {
        $this->response = $this->createMock(ResponseInterface::class);
        $this->request = $this->createMock(RequestInterface::class);
        $this->httpClient = $this->createMock(HttpClient::class);
        $this->requestFactory = $this->createMock(RequestFactory::class);
        $this->hydrator = $this->createMock(Hydrator::class);
    }

    public function configureMessage(string $action, string $uri, array $headers = [], string $body = null): void
    {
        $this->requestFactory
            ->expects($this->once())
            ->method('createRequest')
            ->with($action, $uri, $headers, $body)
            ->willReturn($this->request)
        ;
    }

    public function configureRequestAndResponse(int $responseCode, string $responseBody = '', array $headers = [], $contentType = 'application/json'): void
    {
        $this->response->method('getStatusCode')
            ->willReturn($responseCode)
        ;

        $bodyStream = (new Psr17Factory())->createStream($responseBody);
        $this->response->method('getBody')
            ->willReturn($bodyStream)
        ;

        $headersMap = [];
        foreach ($headers as $key => $value) {
            $headersMap[] = [$key, $value];
        }
        $this->response->method('getHeader')
            ->willReturnMap($headersMap)
        ;

        $this->response->method('getHeaderLine')
            ->with('Content-Type')
            ->willReturn($contentType)
        ;

        $this->httpClient->method('sendRequest')
            ->willReturn($this->response)
        ;
    }

    public function configureHydrator(string $class, $object = null): void
    {
        $this->hydrator
            ->expects($this->once())
            ->method('hydrate')
            ->with($this->response, $class)
            ->willReturn($object)
        ;
    }

    public function getErrorCodesExceptions(): array
    {
        return [
            '400' => [ValidationException::class, 400],
            '401' => [MissingAccessTokenException::class, 401],
            '403' => [PermissionDeniedException::class, 403],
            '404' => [NotFoundException::class, 404],
            '501' => [DisabledFeatureException::class, 501],
            '500' => [ApiException::class, 500],
            // Weird response
            '000' => [ApiException::class, 000],
        ];
    }
}
