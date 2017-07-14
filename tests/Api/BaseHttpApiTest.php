<?php

namespace Pnz\MattermostClient\Tests\Api;

use Http\Client\HttpClient;
use Http\Message\Decorator\ResponseDecorator;
use Http\Message\MessageFactory;
use Http\Message\StreamFactory\GuzzleStreamFactory;
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
    /** @var HttpClient|\PHPUnit_Framework_MockObject_MockObject */
    protected $httpClient;

    /** @var ResponseDecorator|\PHPUnit_Framework_MockObject_MockObject */
    protected $response;

    /** @var RequestInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $request;

    /** @var Hydrator|\PHPUnit_Framework_MockObject_MockObject */
    protected $hydrator;

    /** @var MessageFactory|\PHPUnit_Framework_MockObject_MockObject */
    protected $messageFactory;

    public function setUp()
    {
        $this->response = $this->createMock(ResponseInterface::class);
        $this->request = $this->createMock(RequestInterface::class);
        $this->httpClient = $this->createMock(HttpClient::class);
        $this->messageFactory = $this->createMock(MessageFactory::class);
        $this->hydrator = $this->createMock(Hydrator::class);
    }

    public function configureMessage(string $action, string $uri, array $headers = [], string $body = null)
    {
        $this->messageFactory
            ->expects($this->once())
            ->method('createRequest')
            ->with($action, $uri, $headers, $body)
            ->willReturn($this->request);
    }

    public function configureRequestAndResponse(int $responseCode, string $body = '', array $headers = [], $contentType = 'application/json')
    {
        $this->response->method('getStatusCode')
            ->willReturn($responseCode);

        $bodyStream = (new GuzzleStreamFactory())->createStream($body);
        $this->response->method('getBody')
            ->willReturn($bodyStream);

        $headersMap = [];
        foreach ($headers as $key => $value) {
            $headersMap[] = [$key, $value];
        }
        $this->response->method('getHeader')
            ->willReturnMap($headersMap);

        $this->response->method('getHeaderLine')
            ->with('Content-Type')
            ->willReturn($contentType);

        $this->httpClient->method('sendRequest')
            ->willReturn($this->response);
    }

    public function configureHydrator($class)
    {
        $this->hydrator
            ->expects($this->once())
            ->method('hydrate')
            ->with($this->response, $class);
    }

    public function getErrorCodesExceptions()
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
