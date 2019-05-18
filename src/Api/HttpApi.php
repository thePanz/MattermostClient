<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Api;

use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Pnz\JsonException\Json;
use Pnz\MattermostClient\Exception\ApiException;
use Pnz\MattermostClient\Exception\Domain as DomainExceptions;
use Pnz\MattermostClient\Hydrator\Hydrator;
use Pnz\MattermostClient\Hydrator\ModelHydrator;
use Pnz\MattermostClient\Model\Error;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

abstract class HttpApi
{
    /**
     * @var RequestFactory
     */
    protected $requestFactory;
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var Hydrator
     */
    private $hydrator;

    public function __construct(
        HttpClient $httpClient,
        RequestFactory $messageFactory,
        Hydrator $hydrator = null
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $messageFactory;
        $this->hydrator = $hydrator ?: new ModelHydrator();
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param string $path           Request path
     * @param array  $params         GET parameters
     * @param array  $requestHeaders Request Headers
     */
    protected function httpGet(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        $path .= self::buildPathParams($params);

        return $this->httpClient->sendRequest(
            $this->requestFactory->createRequest('GET', $path, $requestHeaders)
        );
    }

    /**
     * Send a POST request with JSON-encoded parameters.
     *
     * @param string $path           Request path
     * @param array  $params         POST parameters to be JSON encoded
     * @param array  $pathParams     Params added to the path, as query parameters
     * @param array  $requestHeaders Request headers
     */
    protected function httpPost(string $path, array $params = [], array $pathParams = [], array $requestHeaders = []): ResponseInterface
    {
        $body = $this->createJsonBody($params);
        $path .= self::buildPathParams($pathParams);

        return $this->httpPostRaw($path, $body, $requestHeaders);
    }

    /**
     * Send a POST request with raw data.
     *
     * @param string                      $path           Request path
     * @param StreamInterface|string|null $body           Request body
     * @param array                       $requestHeaders Request headers
     */
    protected function httpPostRaw(string $path, $body, array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->requestFactory->createRequest('POST', $path, $requestHeaders, $body)
        );
    }

    /**
     * Send a PUT request with JSON-encoded parameters.
     *
     * @param string $path           Request path
     * @param array  $params         POST parameters to be JSON encoded
     * @param array  $requestHeaders Request headers
     */
    protected function httpPut(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->requestFactory->createRequest('PUT', $path, $requestHeaders, $this->createJsonBody($params))
        );
    }

    /**
     * Send a DELETE request with JSON-encoded parameters.
     *
     * @param string $path           Request path
     * @param array  $params         POST parameters to be JSON encoded
     * @param array  $pathParams     URL parameters, used as query string
     * @param array  $requestHeaders Request headers
     */
    protected function httpDelete(string $path, array $params = [], array $pathParams = [], array $requestHeaders = []): ResponseInterface
    {
        $path .= self::buildPathParams($pathParams);

        return $this->httpClient->sendRequest(
            $this->requestFactory->createRequest('DELETE', $path, $requestHeaders, $this->createJsonBody($params))
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
        $returnCode = $response->getStatusCode();
        if (200 !== $returnCode && 201 !== $returnCode) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, $class);
    }

    /**
     * Handle HTTP errors.
     *
     * Call is controlled by the specific API methods.
     *
     * @throws ApiException
     */
    protected function handleErrors(ResponseInterface $response): void
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
                throw new ApiException($response, $error);
        }
    }

    /**
     * Create a JSON encoded version of an array of parameters.
     */
    private function createJsonBody(array $params): ?string
    {
        return (0 === \count($params)) ? null : Json::encode($params, empty($params) ? JSON_FORCE_OBJECT : 0);
    }

    /**
     * Returns the Query string for the given parameters.
     */
    private static function buildPathParams(array $params): string
    {
        if (\count($params) > 0) {
            return '?'.http_build_query($params);
        }

        return '';
    }
}
