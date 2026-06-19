<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model;

final class MockModel
{
    /**
     * @param array<mixed> $data
     */
    public function __construct(public array $data) {}
}
