<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Api;

use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Pnz\MattermostClient\Exception\Domain as DomainExceptions;
use Pnz\MattermostClient\Exception\GenericApiException;
use Pnz\MattermostClient\Hydrator\Hydrator;
use Pnz\MattermostClient\Hydrator\ModelHydrator;
use Pnz\MattermostClient\Model\Error;
use Psr\Http\Message\ResponseInterface;

abstract class HttpApi
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var Hydrator
     */
    protected $hydrator;

    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * @param HttpClient     $httpClient
     * @param MessageFactory $messageFactory
     * @param Hydrator       $hydrator
     */
    public function __construct(
        HttpClient $httpClient,
        MessageFactory $messageFactory,
        Hydrator $hydrator = null
    ) {
        $this->httpClient = $httpClient;
        $this->messageFactory = $messageFactory;
        $this->hydrator = $hydrator ?: new ModelHydrator();
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param string $path           Request path
     * @param array  $params         GET parameters
     * @param array  $requestHeaders Request Headers
     *
     * @return ResponseInterface
     */
    protected function httpGet(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        if (count($params) > 0) {
            $path .= '?'.http_build_query($params);
        }

        return $this->httpClient->sendRequest(
            $this->messageFactory->createRequest('GET', $path, $requestHeaders)
        );
    }

    /**
     * Send a POST request with JSON-encoded parameters.
     *
     * @param string $path           Request path
     * @param array  $params         POST parameters to be JSON encoded
     * @param array  $pathParams     Params added to the path, as query parameters
     * @param array  $requestHeaders Request headers
     *
     * @return ResponseInterface
     */
    protected function httpPost(string $path, array $params = [], array $pathParams = [], array $requestHeaders = []): ResponseInterface
    {
        $body = $this->createJsonBody($params);

        if (count($pathParams) > 0) {
            $path .= '?'.http_build_query($pathParams);
        }

        return $this->httpPostRaw($path, $body, $requestHeaders);
    }

    /**
     * Send a POST request with raw data.
     *
     * @param string       $path           Request path
     * @param array|string $body           Request body
     * @param array        $requestHeaders Request headers
     *
     * @return ResponseInterface
     */
    protected function httpPostRaw(string $path, $body, array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->messageFactory->createRequest('POST', $path, $requestHeaders, $body)
        );
    }

    /**
     * Send a PUT request with JSON-encoded parameters.
     *
     * @param string $path           Request path
     * @param array  $params         POST parameters to be JSON encoded
     * @param array  $requestHeaders Request headers
     *
     * @return ResponseInterface
     */
    protected function httpPut(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->messageFactory->createRequest('PUT', $path, $requestHeaders, $this->createJsonBody($params))
        );
    }

    /**
     * Send a DELETE request with JSON-encoded parameters.
     *
     * @param string $path           Request path
     * @param array  $params         POST parameters to be JSON encoded
     * @param array  $requestHeaders Request headers
     *
     * @return ResponseInterface
     */
    protected function httpDelete(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->messageFactory->createRequest('DELETE', $path, $requestHeaders, $this->createJsonBody($params))
        );
    }

    /**
     * Handle responses from the endpoint: handle errors and hydrations.
     *
     * @param ResponseInterface $response The request response
     * @param string            $class    The Class to hydrate the response
     *
     * @return mixed Hydration return data
     */
    protected function handleResponse(ResponseInterface $response, $class)
    {
        if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 201) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, $class);
    }

    /**
     * Handle HTTP errors.
     *
     * Call is controlled by the specific API methods.
     *
     * @param ResponseInterface $response
     *
     * @throws GenericApiException
     */
    protected function handleErrors(ResponseInterface $response)
    {
        $error = null;
        // We only hydrate the Error response if the hydrator is a Model one
        if ($this->hydrator instanceof ModelHydrator) {
            $error = $this->hydrator->hydrate($response, Error::class);
        }

        switch ($response->getStatusCode()) {
            case 400:
                throw new DomainExceptions\ValidationException($response, $error);
            case 401:
                throw new DomainExceptions\MissingAccessTokenException($response, $error);
            case 403:
                throw new DomainExceptions\PermissionDeniedException($response, $error);
            case 404:
                throw new DomainExceptions\NotFoundException($response, $error);
            case 501:
                throw new DomainExceptions\DisabledFeatureException($response, $error);
            default:
                throw new GenericApiException($response, $error);
        }
    }

    /**
     * Create a JSON encoded version of an array of parameters.
     *
     * @param array $params Request parameters
     *
     * @return null|string
     */
    private function createJsonBody(array $params)
    {
        return (count($params) === 0) ? null : json_encode($params, empty($params) ? JSON_FORCE_OBJECT : 0);
    }
}
