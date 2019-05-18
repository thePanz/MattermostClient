<?php

namespace Pnz\MattermostClient\Tests\Plugin;

use Http\Message\RequestFactory;
use Http\Promise\Promise;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pnz\JsonException\Json;
use Pnz\MattermostClient\Plugin\MattermostLoginPlugin;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @covers \Pnz\MattermostClient\Plugin\MattermostLoginPlugin
 */
class MattermostLoginPluginTest extends TestCase
{
    public function testMattermostLoginPlugin(): void
    {
        $loginId = 'login-id';
        $password = 'password';

        /** @var RequestInterface|MockObject $loginRequest */
        $loginRequest = $this->createMock(RequestInterface::class);

        /** @var RequestFactory|MockObject $requestFactory */
        $requestFactory = $this->createMock(RequestFactory::class);
        $requestFactory->expects($this->once())
            ->method('createRequest')
            ->with('POST', '/users/login')
            ->willReturn($loginRequest);
        $loginRequest->method('withBody')
            ->with($this->callback(function (StreamInterface $value) use ($loginId, $password) {
                $value->rewind();

                $loginData = Json::encode([
                    'login_id' => $loginId,
                    'password' => $password,
                ], JSON_FORCE_OBJECT);

                $this->assertSame($loginData, $value->getContents());

                return true;
            }))
            ->willReturn($loginRequest);

        /** @var RequestInterface|MockObject $originalRequest */
        $originalRequest = $this->createMock(RequestInterface::class);

        $plugin = new MattermostLoginPlugin($loginId, $password, $requestFactory);

        $plugin->handleRequest(
            $originalRequest,
            function () {},
            function (RequestInterface $request) use ($loginRequest) {
                $this->assertSame($loginRequest, $request);

                $response = $this->createMock(ResponseInterface::class);
                $response->method('getStatusCode')->willReturn(200);

                $response->method('getHeader')
                    ->with('Token')
                    ->willReturn(['token-value']);

                $promise = $this->createMock(Promise::class);
                $promise->expects($this->once())->method('wait')->willReturn($response);

                return $promise;
            }
        );
    }
}
