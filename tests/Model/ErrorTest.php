<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\Error;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\Error
 */
class ErrorTest extends TestCase
{
    public function testErrorCreationEmpty(): void
    {
        $data = [];

        $error = Error::createFromArray($data);

        $this->assertSame('', $error->getId());
        $this->assertSame('', $error->getMessage());
        $this->assertSame(0, $error->getStatusCode());
        $this->assertSame('', $error->getDetailedError());
        $this->assertSame('', $error->getRequestId());
    }

    public function testErrorCreation(): void
    {
        $data = [
            'id' => 'Id',
            'message' => 'Data for: message',
            'status_code' => 12,
            'detailed_error' => 'Detailed Error',
            'request_id' => 'RequestId',
        ];

        $error = Error::createFromArray($data);

        $this->assertSame($data['id'], $error->getId());
        $this->assertSame($data['message'], $error->getMessage());
        $this->assertSame($data['status_code'], $error->getStatusCode());
        $this->assertSame($data['detailed_error'], $error->getDetailedError());
        $this->assertSame($data['request_id'], $error->getRequestId());
    }
}
