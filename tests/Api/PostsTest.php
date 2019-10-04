<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Api;

use Pnz\JsonException\Json;
use Pnz\MattermostClient\Api\PostsApi;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Post\Post;
use Pnz\MattermostClient\Model\Status;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Api\PostsApi
 */
class PostsTest extends BaseHttpApiTest
{
    /**
     * @var PostsApi
     */
    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new PostsApi($this->httpClient, $this->requestFactory, $this->hydrator);
    }

    public function testCreatePostSuccess(): void
    {
        $data = [
            'message' => 'message',
            'channel_id' => 'channel_id,',
        ];
        $this->configureMessage('POST', '/posts', [], Json::encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Post::class);

        $this->client->createPost($data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testCreatePostException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $data = [
            'message' => 'message',
            'channel_id' => 'channel_id,',
        ];
        $this->configureMessage('POST', '/posts', [], Json::encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->createPost($data);
    }

    public function testUpdatePostSuccess(): void
    {
        $postId = '111';
        $data = [
            'message' => 'message,',
            'channel_id' => 'channel_id',
        ];

        $this->configureMessage('PUT', '/posts/'.$postId, [], Json::encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Post::class);
        $this->client->updatePost($postId, $data);
    }

    public function testUpdatePostEmptyId(): void
    {
        $this->expectException(InvalidArgumentException ::class);
        $this->client->updatePost('', []);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testUpdatePostException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $postId = '111';
        $data = [
            'channel_id' => 'channel_id',
            'message' => 'message,',
        ];

        $this->configureMessage('PUT', '/posts/'.$postId, [], Json::encode($data));
        $this->configureRequestAndResponse($code);
        $this->client->updatePost($postId, $data);
    }

    public function testPatchPostSuccess(): void
    {
        $postId = '111';
        $data = [
            'username' => 'username',
            'message' => 'message,',
        ];
        $this->configureMessage('PUT', '/posts/'.$postId.'/patch', [], Json::encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Post::class);

        $this->client->patchPost($postId, $data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testPatchPostException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $postId = '111';
        $data = [
            'username' => 'username',
            'message' => 'message,',
        ];
        $this->configureMessage('PUT', '/posts/'.$postId.'/patch', [], Json::encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->patchPost($postId, $data);
    }

    public function testPatchPostsEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->patchPost('', []);
    }

    public function testGetPostSuccess(): void
    {
        $postId = '12345';
        $this->configureMessage('GET', '/posts/'.$postId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Post::class);
        $this->client->getPost($postId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetPostException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $postId = '12345';
        $this->configureMessage('GET', '/posts/'.$postId);
        $this->configureRequestAndResponse($code);
        $this->client->getPost($postId);
    }

    public function testGetPostEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getPost('');
    }

    public function testDeletePostSuccess(): void
    {
        $postId = '12345';
        $this->configureMessage('DELETE', '/posts/'.$postId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->deletePost($postId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testDeletePostException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $postId = '12345';
        $this->configureMessage('DELETE', '/posts/'.$postId);
        $this->configureRequestAndResponse($code);
        $this->client->deletePost($postId);
    }

    public function testDeletePostEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->deletePost('');
    }

    public function testPinPostSuccess(): void
    {
        $postId = '12345';
        $this->configureMessage('POST', '/posts/'.$postId.'/pin');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->pinPost($postId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testPinPostException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $postId = '12345';
        $this->configureMessage('POST', '/posts/'.$postId.'/pin');
        $this->configureRequestAndResponse($code);
        $this->client->pinPost($postId);
    }

    public function testPinPostEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->pinPost('');
    }

    public function testUnpinPostSuccess(): void
    {
        $postId = '12345';
        $this->configureMessage('POST', '/posts/'.$postId.'/unpin');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->unpinPost($postId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testUnpinPostException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $postId = '12345';
        $this->configureMessage('POST', '/posts/'.$postId.'/unpin');
        $this->configureRequestAndResponse($code);
        $this->client->unpinPost($postId);
    }

    public function testUnpinPostEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->unpinPost('');
    }
}
