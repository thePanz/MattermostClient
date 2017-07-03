<?php

namespace Pnz\MattermostClient\Tests\Model;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\Status;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\Status
 */
class StatusTest extends TestCase
{
    public function testStatusCreationEmpty()
    {
        $data = [];

        $error = Status::createFromArray($data);

        $this->assertSame('', $error->getStatus());
    }

    public function testStatusCreation()
    {
        $data = [
            'status' => 'Status',
        ];

        $error = Status::createFromArray($data);

        $this->assertSame($data['status'], $error->getStatus());
    }
}
