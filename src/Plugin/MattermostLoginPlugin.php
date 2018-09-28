<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Plugin;

use Http\Client\Common\Plugin;
use Http\Message\Authentication\Bearer;
use Http\Message\RequestFactory;
use Pnz\MattermostClient\Exception\ApiException;
use Pnz\MattermostClient\Exception\Domain\LoginFailedException;
use Pnz\MattermostClient\Model\Error;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class MattermostLoginPlugin implements Plugin
{
    const AUTHORIZATION_URL = '/users/login';

    /**
     * Flag to check if we are handling the first login through a request.
     *
     * @var bool
     */
    private $loginInProgress = false;

    /**
     * @var string
     */
    private $loginId;

    /**
     * @var string
     */
    private $password;

    /**
     * @var Bearer
     */
    private $bearerAuthentication;

    /**
     * @var RequestFactory
     */
    private $factory;

    public function __construct(string $loginId, string $password, RequestFactory $factory)
    {
        $this->loginId = $loginId;
        $this->password = $password;
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        if (!$this->bearerAuthentication && !$this->loginInProgress) {
            $this->loginInProgress = true;
            $this->authenticate($first);
        }

        // If we have a Bearer authentication (with token) use it
        if ($this->bearerAuthentication) {
            $request = $this->bearerAuthentication->authenticate($request);
        }

        return $next($request);
    }

    /**
     * @param callable $first
     *
     * @throws LoginFailedException when loginId/password are not valid
     * @throws ApiException         When an unknown response is returned by the API while logging in
     */
    private function authenticate(callable $first)
    {
        $credentials = [
            'login_id' => $this->loginId,
            'password' => $this->password,
        ];

        $request = $this->factory->createRequest(
            'POST',
            self::AUTHORIZATION_URL,
            [],
            json_encode($credentials, JSON_FORCE_OBJECT)
        );

        /* @var ResponseInterface $response */
        $response = $first($request)->wait();

        switch ($response->getStatusCode()) {
            case 200:
                $tokens = $response->getHeader('Token');
                if (count($tokens) && $token = reset($tokens)) {
                    $this->bearerAuthentication = new Bearer($token);
                }
                break;
            case 401:
                if (0 === strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
                    $contents = json_decode((string) $response->getBody(), true);
                    $error = Error::createFromArray($contents);
                    throw new LoginFailedException($response, $error);
                }
                // Otherwise fallback to the default exception
                // no break
            default:
                throw new ApiException($response);
        }
    }
}
