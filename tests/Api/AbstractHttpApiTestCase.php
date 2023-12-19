<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Api;

use Http\Message\RequestMatcher\CallbackRequestMatcher;
use Http\Mock\Client;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Contract\HydratorInterface;
use Pnz\MattermostClient\Exception\ApiException;
use Pnz\MattermostClient\Exception\Domain\DisabledFeatureException;
use Pnz\MattermostClient\Exception\Domain\MissingAccessTokenException;
use Pnz\MattermostClient\Exception\Domain\NotFoundException;
use Pnz\MattermostClient\Exception\Domain\PermissionDeniedException;
use Pnz\MattermostClient\Exception\Domain\ValidationException;
use Pnz\MattermostClient\Exception\DomainException;
use Pnz\MattermostClient\Model\CreatableFromArray;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractHttpApiTestCase extends TestCase
{
    protected Client $httpClient;
    protected Psr17Factory $psr17factory;
    /** @var HydratorInterface&MockObject */
    protected HydratorInterface $hydrator;

    protected const CHANNEL_UUID = '5f130d6c-496a-4925-b68c-7daacf95e5b5';
    protected const CHANNEL_NAME = 'channel-name';
    protected const TEAM_ID = '12345';
    protected const TEAM_NAME = 'team-name';
    protected const USER_UUID = '66108c86-fa15-46c0-98ef-05f9d538bb3a';
    protected const USER_UUID2 = '4807c214-1a15-422d-8136-d4e6d1d903ca';
    protected const POST_UUID = 'e79f55c3-5c27-4132-a98c-5cbeb086bbed';
    protected const TEAM_INVITE_ID = '569ebc5f-8d4b-42b5-9ec4-5729c3190d05';
    protected const USER_EMAIL = 'user@example.com';
    protected const USER_PASSWORD = '90ef6f5c4b08aabc';
    protected const USER_PASSWORD2 = '483088f3abfbb8da';
    protected const USER_USERNAME = 'UserName';
    protected const FILE_UUID = '489c7169-a23e-4943-8f59-2f2a3d0f2b12';

    protected function setUp(): void
    {
        $this->psr17factory = new Psr17Factory();
        $this->httpClient = new Client($this->psr17factory);

        $this->hydrator = $this->createMock(HydratorInterface::class);
    }

    /**
     * @param array<mixed>|string            $body
     * @param array<string, string|string[]> $headers
     */
    public function buildResponse(
        int $code,
        array|string $body = [],
        array $headers = [],
        string $contentType = 'application/json'
    ): ResponseInterface {
        $response = $this->psr17factory->createResponse($code);

        $contents = \is_string($body) ? $body : json_encode($body, \JSON_THROW_ON_ERROR);
        $response = $response->withBody($this->psr17factory->createStream($contents));

        foreach ($headers as $header => $value) {
            $response = $response->withHeader($header, $value);
        }

        return $response->withHeader('Content-Type', $contentType);
    }

    /**
     * @param string|array<mixed> $body
     */
    protected function expectRequest(string $method, string $uri, array|string $body, ResponseInterface $response): void
    {
        $jsonBody = json_encode($body, \JSON_THROW_ON_ERROR);
        $this->httpClient->on(new CallbackRequestMatcher(static function (RequestInterface $request) use ($method, $uri): bool {
            if ($request->getMethod() !== $method) {
                return false;
            }
            if ($request->getUri()->__toString() !== $uri) {
                return false;
            }

            return true;
        }), $response);

        $this->httpClient->setDefaultException(new \RuntimeException('This HTTP call was not Expected to be called!'));
    }

    /**
     * @param class-string<CreatableFromArray> $class
     */
    public function expectHydration(ResponseInterface $response, string $class): void
    {
        $this->hydrator
            ->expects($this->once())
            ->method('hydrate')
            ->with($response, $class)
            ->willReturn($class::createFromArray(
                json_decode((string) $response->getBody(), true, 512, \JSON_THROW_ON_ERROR)
            ))
        ;
    }

    /**
     * @return iterable<array{class-string<DomainException>, int}>
     */
    public static function provideErrorCodesExceptionsCases(): iterable
    {
        yield '400' => [ValidationException::class, 400];
        yield '401' => [MissingAccessTokenException::class, 401];
        yield '403' => [PermissionDeniedException::class, 403];
        yield '404' => [NotFoundException::class, 404];
        yield '501' => [DisabledFeatureException::class, 501];
        yield '500' => [ApiException::class, 500];
        // Weird response
        yield '000' => [ApiException::class, 000];
    }
}
