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

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $loginId;

    /**
     * @var string string
     */
    private $password;

    /**
     * @var string|null
     */
    private $token;

    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    public function __construct(
        string $loginId,
        string $password,
        ClientInterface $client,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->loginId = $loginId;
        $this->password = $password;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->client = $client;
    }

    public function authenticate(RequestInterface $request): RequestInterface
    {
        if (!$this->token) {
            $this->getToken();
        }

        $header = sprintf('Bearer %s', $this->token);

        return $request->withHeader('Authorization', $header);
    }

    private function getToken(): void
    {
        $credentials = [
            'login_id' => $this->loginId,
            'password' => $this->password,
        ];

        $request = $this->requestFactory->createRequest('POST', self::AUTHORIZATION_URL)
            ->withBody($this->streamFactory->createStream(
                Json::encode($credentials, JSON_FORCE_OBJECT)
            ))
        ;

        $response = $this->client->sendRequest($request);

        // We got a non-json response, can not continue!
        if (0 !== strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
            throw new ApiException($response);
        }

        switch ($response->getStatusCode()) {
            case 200:
                $this->token = $response->getHeaderLine('Token');

                return;
            case 401:
                $contents = Json::decode((string) $response->getBody(), true);
                $error = Error::createFromArray($contents);
                throw new LoginFailedException($response, $error);
        }

        throw new ApiException($response);
    }
}
