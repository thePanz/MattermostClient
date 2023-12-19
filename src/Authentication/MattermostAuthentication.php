<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Authentication;

use Http\Message\Authentication;
use Pnz\JsonException\Json;
use Pnz\MattermostClient\Exception\ApiException;
use Pnz\MattermostClient\Exception\Domain\LoginFailedException;
use Pnz\MattermostClient\Model\Error;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

class MattermostAuthentication implements Authentication
{
    private const AUTHORIZATION_URL = '/users/login';

    private ?string $token = null;

    public function __construct(
        private readonly string $loginId,
        private readonly string $password,
        private readonly ClientInterface $client,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory
    ) {}

    public function authenticate(RequestInterface $request): RequestInterface
    {
        if (null === $this->token) {
            $this->token = $this->obtainTokenByLogin();
        }

        $header = sprintf('Bearer %s', $this->token);

        return $request->withHeader('Authorization', $header);
    }

    private function obtainTokenByLogin(): string
    {
        $credentials = [
            'login_id' => $this->loginId,
            'password' => $this->password,
        ];

        $request = $this->requestFactory->createRequest('POST', self::AUTHORIZATION_URL)
            ->withBody($this->streamFactory->createStream(
                json_encode($credentials, \JSON_FORCE_OBJECT | \JSON_THROW_ON_ERROR)
            ))
        ;

        $response = $this->client->sendRequest($request);

        // We got a non-json response, can not continue!
        if (!str_starts_with($response->getHeaderLine('Content-Type'), 'application/json')) {
            throw new ApiException($response);
        }

        switch ($response->getStatusCode()) {
            case 200:
                return $response->getHeaderLine('Token');

            case 401:
                $contents = json_decode((string) $response->getBody(), true, 512, \JSON_THROW_ON_ERROR);
                $error = Error::createFromArray($contents);
                throw new LoginFailedException($response, $error);
        }

        throw new ApiException($response);
    }
}
