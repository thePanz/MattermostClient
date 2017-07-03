<?php

namespace Pnz\MattermostClient\Tests\Api;

use Pnz\MattermostClient\Api\Posts;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Post\Post;
use Pnz\MattermostClient\Model\Status;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Api\Posts
 */
class PostsTest extends BaseHttpApiTest
{
    /**
     * @var Posts
     */
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = new Posts($this->httpClient, $this->messageFactory, $this->hydrator);
    }

    public function testCreatePostSuccess()
    {
        $data = [
            'message' => 'message',
            'channel_id' => 'channel_id,',
        ];
        $this->configureMessage('POST', '/posts', [], json_encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Post::class);

        $this->client->createPost($data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testCreatePostException($exception, $code)
    {
        $this->expectException($exception);
        $data = [
            'message' => 'message',
            'channel_id' => 'channel_id,',
        ];
        $this->configureMessage('POST', '/posts', [], json_encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->createPost($data);
    }

    public function testUpdatePostSuccess()
    {
        $postId = '111';
        $data = [
            'message' => 'message,',
            'channel_id' => 'channel_id',
        ];

        $this->configureMessage('PUT', '/posts/'.$postId, [], json_encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Post::class);
        $this->client->updatePost($postId, $data);
    }

    public function testUpdatePostEmptyId()
    {
        $this->expectException(InvalidArgumentException ::class);
        $this->client->updatePost('', []);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testUpdatePostException($exception, $code)
    {
        $this->expectException($exception);
        $postId = '111';
        $data = [
            'channel_id' => 'channel_id',
            'message' => 'message,',
        ];

        $this->configureMessage('PUT', '/posts/'.$postId, [], json_encode($data));
        $this->configureRequestAndResponse($code);
        $this->client->updatePost($postId, $data);
    }

    public function testPatchPostSuccess()
    {
        $postId = '111';
        $data = [
            'username' => 'username',
            'message' => 'message,',
        ];
        $this->configureMessage('PUT', '/posts/'.$postId.'/patch', [], json_encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Post::class);

        $this->client->patchPost($postId, $data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testPatchPostException($exception, $code)
    {
        $this->expectException($exception);
        $postId = '111';
        $data = [
            'username' => 'username',
            'message' => 'message,',
        ];
        $this->configureMessage('PUT', '/posts/'.$postId.'/patch', [], json_encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->patchPost($postId, $data);
    }

    public function testPatchPostsEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->patchPost('', []);
    }

    public function testGetPostSuccess()
    {
        $postId = '12345';
        $this->configureMessage('GET', '/posts/'.$postId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Post::class);
        $this->client->getPost($postId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetPostException($exception, $code)
    {
        $this->expectException($exception);
        $postId = '12345';
        $this->configureMessage('GET', '/posts/'.$postId);
        $this->configureRequestAndResponse($code);
        $this->client->getPost($postId);
    }

    public function testGetPostEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getPost('');
    }

    public function testDeletePostSuccess()
    {
        $postId = '12345';
        $this->configureMessage('DELETE', '/posts/'.$postId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->deletePost($postId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testDeletePostException($exception, $code)
    {
        $this->expectException($exception);
        $postId = '12345';
        $this->configureMessage('DELETE', '/posts/'.$postId);
        $this->configureRequestAndResponse($code);
        $this->client->deletePost($postId);
    }

    public function testDeletePostEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->deletePost('');
    }

    public function testPinPostSuccess()
    {
        $postId = '12345';
        $this->configureMessage('POST', '/posts/'.$postId.'/pin');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->pinPost($postId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testPinPostException($exception, $code)
    {
        $this->expectException($exception);
        $postId = '12345';
        $this->configureMessage('POST', '/posts/'.$postId.'/pin');
        $this->configureRequestAndResponse($code);
        $this->client->pinPost($postId);
    }

    public function testPinPostEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->pinPost('');
    }

    public function testUnpinPostSuccess()
    {
        $postId = '12345';
        $this->configureMessage('POST', '/posts/'.$postId.'/unpin');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->unpinPost($postId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testUnpinPostException($exception, $code)
    {
        $this->expectException($exception);
        $postId = '12345';
        $this->configureMessage('POST', '/posts/'.$postId.'/unpin');
        $this->configureRequestAndResponse($code);
        $this->client->unpinPost($postId);
    }

    public function testUnpinPostEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->unpinPost('');
    }
}
