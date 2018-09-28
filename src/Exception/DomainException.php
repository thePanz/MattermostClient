<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Exception;

use Pnz\MattermostClient\Model\Error;
use Psr\Http\Message\ResponseInterface;

interface DomainException extends Exception
{
    /**
     * @return ResponseInterface
     */
    public function getResponse();

    /**
     * Returns the underlying Error, if available.
     *
     * @return Error|null
     */
    public function getError();
}
