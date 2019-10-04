<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Api;

use PHPUnit\Framework\MockObject\MockObject;
use Pnz\MattermostClient\Hydrator\ModelHydrator;
use Pnz\MattermostClient\Model\Error;
use Psr\Http\Message\ResponseInterface;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Api\HttpApi
 */
class HttpApiTest extends BaseHttpApiTest
{
    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testHandleErrors(string $expectedException, int $responseCode): void
    {
        /** @var ResponseInterface|MockObject $response */
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')
            ->willReturn($responseCode)
        ;

        $httpApi = new WrappedHttpApi($this->httpClient, $this->requestFactory, $this->hydrator);

        $this->expectException($expectedException);
        $httpApi->testHandleError($response);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testHandleErrorsWithModel(string $expectedException, int $responseCode): void
    {
        /** @var ResponseInterface|MockObject $response */
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')
            ->willReturn($responseCode)
        ;

        $error = Error::createFromArray([
            'message' => 'Error code:'.$responseCode,
        ]);

        /** @var ModelHydrator|MockObject $hydrator */
        $hydrator = $this->createMock(ModelHydrator::class);
        $hydrator->method('hydrate')
            ->willReturn($error)
        ;

        $httpApi = new WrappedHttpApi($this->httpClient, $this->requestFactory, $hydrator);

        $this->expectException($expectedException);
        $httpApi->testHandleError($response);
    }
}
