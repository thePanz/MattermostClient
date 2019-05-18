<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Exception;

use Pnz\MattermostClient\Model\Error;
use Psr\Http\Message\ResponseInterface;

class ApiException extends \Exception implements DomainException
{
    /**
     * @var Error|null
     */
    protected $error;

    /**
     * @var ResponseInterface
     */
    protected $response;

    public function __construct(ResponseInterface $response, Error $error = null)
    {
        $this->error = $error;
        $this->response = $response;

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
