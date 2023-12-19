<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model;

interface CreatableFromArray
{
    /**
     * Create an API response object from the HTTP response from the API server.
     *
     * @param mixed[] $data
     */
    public static function createFromArray(array $data): static;
}
