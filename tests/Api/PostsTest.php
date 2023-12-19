<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Pnz\MattermostClient\Api\PostsApi;
use Pnz\MattermostClient\Exception\DomainException;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Error;
use Pnz\MattermostClient\Model\Post\Post;
use Pnz\MattermostClient\Model\Status;

/**
 * @internal
 */
#[CoversClass(PostsApi::class)]
final class PostsTest extends AbstractHttpApiTestCase
{
    private PostsApi $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new PostsApi($this->httpClient, $this->psr17factory, $this->psr17factory, $this->hydrator);
    }

    public function testCreatePostSucceeds(): void
    {
        $requestData = ['message' => 'Lorem Ipsum', 'channel_id' => self::CHANNEL_UUID];
        $responseData = ['id' => self::POST_UUID, 'channel_id' => self::CHANNEL_UUID];
        $response = $this->buildResponse(201, $responseData);

        $this->expectRequest('POST', '/posts', $requestData, $response);
        $this->expectHydration($response, Post::class);

        $p = $this->client->createPost($requestData);

        $this->assertSame(self::CHANNEL_UUID, $p->getChannelId());
        $this->assertSame(self::POST_UUID, $p->getId());
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testCreatePostThrows(string $exception, int $code): void
    {
        $requestData = ['message' => 'Lorem Ipsum', 'channel_id' => self::CHANNEL_UUID];
        $response = $this->buildResponse($code);

        $this->expectRequest('POST', '/posts', $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->createPost($requestData);
    }

    public function testUpdatePostSucceeds(): void
    {
        $requestData = ['message' => 'Lorem Ipsum', 'channel_id' => self::CHANNEL_UUID];
        $responseData = ['id' => self::POST_UUID, 'channel_id' => self::CHANNEL_UUID];
        $response = $this->buildResponse(201, $responseData);

        $this->expectRequest('PUT', '/posts/'.self::POST_UUID, $requestData, $response);
        $this->expectHydration($response, Post::class);

        $p = $this->client->updatePost(self::POST_UUID, $requestData);

        $this->assertSame(self::CHANNEL_UUID, $p->getChannelId());
        $this->assertSame(self::POST_UUID, $p->getId());
    }

    public function testUpdatePostWithEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->updatePost('', []);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testUpdatePostThrows(string $exception, int $code): void
    {
        $requestData = ['message' => 'Lorem Ipsum', 'channel_id' => self::CHANNEL_UUID];
        $response = $this->buildResponse($code);

        $this->expectRequest('PUT', '/posts/'.self::POST_UUID, $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->updatePost(self::POST_UUID, $requestData);
    }

    public function testPatchPostSucceeds(): void
    {
        $requestData = ['message' => 'Lorem Lipsum'];
        $responseData = ['id' => self::POST_UUID, 'channel_id' => self::CHANNEL_UUID];

        $response = $this->buildResponse(201, $responseData);
        $this->expectRequest('PUT', '/posts/'.self::POST_UUID.'/patch', $requestData, $response);
        $this->expectHydration($response, Post::class);

        $p = $this->client->patchPost(self::POST_UUID, $requestData);

        $this->assertSame(self::CHANNEL_UUID, $p->getChannelId());
        $this->assertSame(self::POST_UUID, $p->getId());
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testPatchPostThrows(string $exception, int $code): void
    {
        $requestData = ['message' => 'Lorem Ipsum'];
        $response = $this->buildResponse($code);

        $this->expectRequest('PUT', '/posts/'.self::POST_UUID.'/patch', $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->patchPost(self::POST_UUID, $requestData);
    }

    public function testPatchPostWithEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->patchPost('', []);
    }

    public function testGetPostSucceeds(): void
    {
        $responseData = ['id' => self::POST_UUID, 'channel_id' => self::CHANNEL_UUID];

        $response = $this->buildResponse(200, $responseData);
        $this->expectRequest('GET', '/posts/'.self::POST_UUID, [], $response);
        $this->expectHydration($response, Post::class);

        $p = $this->client->getPost(self::POST_UUID);

        $this->assertSame(self::CHANNEL_UUID, $p->getChannelId());
        $this->assertSame(self::POST_UUID, $p->getId());
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetPostThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('GET', '/posts/'.self::POST_UUID, [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getPost(self::POST_UUID);
    }

    public function testGetPostWithEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getPost('');
    }

    public function testDeletePostSucceeds(): void
    {
        $response = $this->buildResponse(200);
        $this->expectRequest('DELETE', '/posts/'.self::POST_UUID, [], $response);
        $this->expectHydration($response, Status::class);
        $this->client->deletePost(self::POST_UUID);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testDeletePostThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('DELETE', '/posts/'.self::POST_UUID, [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->deletePost(self::POST_UUID);
    }

    public function testDeletePostWithEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->deletePost('');
    }

    public function testPinPostSucceeds(): void
    {
        $response = $this->buildResponse(200);
        $this->expectRequest('POST', '/posts/'.self::POST_UUID.'/pin', [], $response);
        $this->expectHydration($response, Status::class);

        $this->client->pinPost(self::POST_UUID);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testPinPostThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('POST', '/posts/'.self::POST_UUID.'/pin', [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->pinPost(self::POST_UUID);
    }

    public function testPinPostWithEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->pinPost('');
    }

    public function testUnpinPostSucceeds(): void
    {
        $response = $this->buildResponse(200);
        $this->expectRequest('POST', '/posts/'.self::POST_UUID.'/unpin', [], $response);
        $this->expectHydration($response, Status::class);

        $this->client->unpinPost(self::POST_UUID);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testUnpinPostThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('POST', '/posts/'.self::POST_UUID.'/unpin', [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->unpinPost(self::POST_UUID);
    }

    public function testUnpinPostWithEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->unpinPost('');
    }
}
