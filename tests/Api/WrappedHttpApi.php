<?php

namespace Pnz\MattermostClient\Tests\Api;

use Pnz\MattermostClient\Api\HttpApi;
use Psr\Http\Message\ResponseInterface;

class WrappedHttpApi extends HttpApi
{
    public function testHandleError(ResponseInterface $response)
    {
        self::handleErrors($response);
    }
}
