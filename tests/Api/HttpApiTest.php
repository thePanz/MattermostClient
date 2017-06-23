<?php

namespace Pnz\MattermostClient\Tests\Api;

use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Exception\Domain\DisabledFeatureException;
use Pnz\MattermostClient\Exception\Domain\MissingAccessTokenException;
use Pnz\MattermostClient\Exception\Domain\NotFoundException;
use Pnz\MattermostClient\Exception\Domain\PermissionDeniedException;
use Pnz\MattermostClient\Exception\Domain\ValidationException;
use Pnz\MattermostClient\Exception\GenericApiException;
use Pnz\MattermostClient\Hydrator\Hydrator;
use Pnz\MattermostClient\Model\Error;
use Psr\Http\Message\ResponseInterface;

/**
 * @coversNothing
 */
class HttpApiTest extends TestCase
{
    public function provideHandleErrorsData()
    {
        return [
            '400' => [ValidationException::class, 400],
            '401' => [MissingAccessTokenException::class, 401],
            '403' => [PermissionDeniedException::class, 403],
            '404' => [NotFoundException::class, 404],
            '501' => [DisabledFeatureException::class, 501],
            '500' => [GenericApiException::class, 500],
            // Weird response
            '000' => [GenericApiException::class, 000],
        ];
    }

    /**
     * @dataProvider provideHandleErrorsData
     *
     * @param string $expectedException
     * @param int    $responseCode
     */
    public function testHandleErrors(string $expectedException, int $responseCode)
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')
            ->willReturn($responseCode);

        $httpClient = $this->createMock(HttpClient::class);
        $messageFactory = $this->createMock(MessageFactory::class);
        $hydrator = $this->createMock(Hydrator::class);

        $httpApi = new WrappedHttpApi($httpClient, $messageFactory, $hydrator);

        $this->expectException($expectedException);
        $httpApi->testHandleError($response);
    }

    /**
     * @dataProvider provideHandleErrorsData
     *
     * @param string $expectedException
     * @param int    $responseCode
     */
    public function testHandleErrorsWithModel(string $expectedException, int $responseCode)
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')
            ->willReturn($responseCode);

        $httpClient = $this->createMock(HttpClient::class);
        $messageFactory = $this->createMock(MessageFactory::class);

        $error = Error::createFromArray([
            'message' => 'Error code:'.$responseCode,
        ]);

        $hydrator = $this->createMock(Hydrator::class);
        $hydrator->method('hydrate')
            ->willReturn($error);

        $httpApi = new WrappedHttpApi($httpClient, $messageFactory, $hydrator);

        $this->expectException($expectedException);
        $httpApi->testHandleError($response);
    }
}
