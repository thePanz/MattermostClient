<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Exception;

use Pnz\MattermostClient\Model\Error;
use Psr\Http\Message\ResponseInterface;

class GenericApiException extends \Exception implements ApiException
{
    /**
     * @var Error
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

    /**
     * @return Error|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }
}
