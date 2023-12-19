<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Exception;

use Pnz\MattermostClient\Model\Error;
use Psr\Http\Message\ResponseInterface;

class ApiException extends \Exception implements DomainException
{
    public function __construct(protected ResponseInterface $response, protected ?Error $error = null)
    {
        parent::__construct($error ? $error->getMessage() : '', $response->getStatusCode());
    }

    public function getError(): ?Error
    {
        return $this->error;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
