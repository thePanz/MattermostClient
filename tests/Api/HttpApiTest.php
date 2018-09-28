<?php

namespace Pnz\MattermostClient\Tests\Api;

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
    public function testHandleErrors(string $expectedException, int $responseCode)
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')
            ->willReturn($responseCode);

        $httpApi = new WrappedHttpApi($this->httpClient, $this->messageFactory, $this->hydrator);

        $this->expectException($expectedException);
        $httpApi->testHandleError($response);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testHandleErrorsWithModel(string $expectedException, int $responseCode)
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')
            ->willReturn($responseCode);

        $error = Error::createFromArray([
            'message' => 'Error code:'.$responseCode,
        ]);

        $hydrator = $this->createMock(ModelHydrator::class);
        $hydrator->method('hydrate')
            ->willReturn($error);

        $httpApi = new WrappedHttpApi($this->httpClient, $this->messageFactory, $hydrator);

        $this->expectException($expectedException);
        $httpApi->testHandleError($response);
    }
}
