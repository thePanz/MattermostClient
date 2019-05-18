<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Exception;

use Pnz\MattermostClient\Model\Error;
use Psr\Http\Message\ResponseInterface;

interface DomainException extends Exception
{
    public function getResponse(): ResponseInterface;

    /**
     * Returns the underlying Error, if available.
     */
    public function getError(): ?Error;
}
