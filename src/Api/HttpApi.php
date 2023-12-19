<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Api;

use Pnz\MattermostClient\Contract\HydratorInterface;
use Pnz\MattermostClient\Exception\ApiException;
use Pnz\MattermostClient\Exception\Domain as DomainExceptions;
use Pnz\MattermostClient\Hydrator\ModelHydrator;
use Pnz\MattermostClient\Model\Error;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @phpstan-type RequestQueryParams array<string, int|string|bool>
 * @phpstan-type RequestHeaders array<string, string|list<string>>
 * @phpstan-type RequestData array<mixed>
 */
abstract class HttpApi
{
    private readonly HydratorInterface $hydrator;

    public function __construct(
        private readonly ClientInterface $httpClient,
        protected RequestFactoryInterface $requestFactory,
        protected StreamFactoryInterface $streamFactory,
        HydratorInterface $hydrator = null
    ) {
        $this->hydrator = $hydrator ?: new ModelHydrator();
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param RequestQueryParams $queryParams
     * @param RequestHeaders     $headers
     */
    protected function httpGet(string $path, array $queryParams = [], array $headers = []): ResponseInterface
    {
        $path .= self::buildUrlQueryParams($queryParams);

        $request = $this->requestFactory->createRequest('GET', $path);
        $request = $this->prepareRequest($request, $headers);

        return $this->httpClient->sendRequest($request);
    }

    /**
     * Send a POST request with JSON-encoded parameters.
     *
     * @param RequestData        $data        POST parameters to be JSON encoded
     * @param RequestQueryParams $queryParams
     * @param RequestHeaders     $headers
     */
    protected function httpPost(string $path, array $data = [], array $queryParams = [], array $headers = []): ResponseInterface
    {
        $body = $this->createJsonBody($data);
        $path .= self::buildUrlQueryParams($queryParams);

        return $this->httpPostRaw($path, $body, $headers);
    }

    /**
     * Send a POST request with raw data.
     *
     * @param RequestHeaders $headers
     */
    protected function httpPostRaw(string $path, ?StreamInterface $body, array $headers = []): ResponseInterface
    {
        $request = $this->requestFactory->createRequest('POST', $path);
        $request = $this->prepareRequest($request, $headers, $body);

        return $this->httpClient->sendRequest($request);
    }

    /**
     * Send a PUT request with JSON-encoded parameters.
     *
     * @param RequestData    $data    PUT parameters to be JSON encoded
     * @param RequestHeaders $headers
     */
    protected function httpPut(string $path, array $data = [], array $headers = []): ResponseInterface
    {
        $request = $this->requestFactory->createRequest('PUT', $path);
        $request = $this->prepareRequest($request, $headers, $this->createJsonBody($data));

        return $this->httpClient->sendRequest($request);
    }

    /**
     * Send a DELETE request with JSON-encoded parameters.
     *
     * @param RequestData        $data           DELETE parameters to be JSON encoded
     * @param RequestQueryParams $queryParams
     * @param RequestHeaders     $requestHeaders
     */
    protected function httpDelete(string $path, array $data = [], array $queryParams = [], array $requestHeaders = []): ResponseInterface
    {
        $path .= self::buildUrlQueryParams($queryParams);
        $request = $this->requestFactory->createRequest('DELETE', $path);
        $request = $this->prepareRequest($request, $requestHeaders, $this->createJsonBody($data));

        return $this->httpClient->sendRequest($request);
    }

    /**
     * Handle responses from the endpoint: handle errors and hydrations.
     *
     * @template T of object
     *
     * @param class-string<T> $class The Class to hydrate the response
     *
     * @return T
     */
    protected function handleResponse(ResponseInterface $response, string $class): object
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
        // We only hydrate the Error response if the hydrator is a Model one
        $error = $this->hydrator->hydrate($response, Error::class);

        throw match ($response->getStatusCode()) {
            400 => new DomainExceptions\ValidationException($response, $error),
            401 => new DomainExceptions\MissingAccessTokenException($response, $error),
            403 => new DomainExceptions\PermissionDeniedException($response, $error),
            404 => new DomainExceptions\NotFoundException($response, $error),
            501 => new DomainExceptions\DisabledFeatureException($response, $error),
            default => new ApiException($response, $error),
        };
    }

    /**
     * Create a JSON encoded version of an array of parameters.
     *
     * @param RequestData $data
     */
    private function createJsonBody(array $data): ?StreamInterface
    {
        if ([] === $data) {
            return null;
        }

        return $this->streamFactory->createStream(json_encode($data, \JSON_THROW_ON_ERROR));
    }

    /**
     * Returns the Query string for the given parameters.
     *
     * @param RequestQueryParams $params
     */
    private static function buildUrlQueryParams(array $params): string
    {
        if ([] === $params) {
            return '';
        }

        return '?'.http_build_query($params);
    }

    /**
     * @param RequestHeaders $headers
     */
    private function prepareRequest(RequestInterface $request, array $headers, StreamInterface $body = null): RequestInterface
    {
        foreach ($headers as $header => $value) {
            $request = $request->withAddedHeader($header, $value);
        }

        if ($body instanceof StreamInterface) {
            $request = $request->withBody($body);
        }

        return $request;
    }
}
