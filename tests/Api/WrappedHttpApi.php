<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Api;

use Pnz\MattermostClient\Api\HttpApi;
use Psr\Http\Message\ResponseInterface;

class WrappedHttpApi extends HttpApi
{
    public function testHandleError(ResponseInterface $response): void
    {
        $this->handleErrors($response);
    }
}
