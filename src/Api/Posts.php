<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Api;

use InvalidArgumentException;
use Pnz\MattermostClient\Model\Post\Post;
use Pnz\MattermostClient\Model\Status;
use Psr\Http\Message\ResponseInterface;

final class Posts extends HttpApi
{
    /**
     * Create a post. Required parameters: 'channel_id', 'message'.
     *
     * @param array $params
     *
     * @return Post|ResponseInterface
     */
    public function createPosts(array $params)
    {
        $response = $this->httpPost('/posts',
            $params
        );

        return $this->handleResponse($response, Post::class);
    }

    /**
     * Patch a post.
     *
     * @see https://api.mattermost.com/v4/#tag/posts%2Fpaths%2F~1posts~1%7Bpost_id%7D~1patch%2Fput
     *
     * @param string $postId
     * @param array  $params
     *
     * @return Post|ResponseInterface
     */
    public function patchPost(string $postId, array $params)
    {
        if (empty($postId)) {
            throw new InvalidArgumentException('PostId can not be empty');
        }

        $response = $this->httpPut(sprintf('/posts/%s/patch', $postId), $params);

        return $this->handleResponse($response, Post::class);
    }

    /**
     * Update a post.
     *
     * @see https://api.mattermost.com/v4/#tag/posts%2Fpaths%2F~1posts~1%7Bpost_id%7D%2Fput
     *
     * @param string $postId
     * @param array  $params
     *
     * @return Post|ResponseInterface
     */
    public function updatePost(string $postId, array $params)
    {
        if (empty($postId)) {
            throw new InvalidArgumentException('PostId can not be empty');
        }

        $response = $this->httpPut(sprintf('/posts/%s', $postId), $params);

        return $this->handleResponse($response, Post::class);
    }

    /**
     * Returns a post by its ID.
     *
     * @param string $postId
     *
     * @return Post|ResponseInterface
     */
    public function getPost($postId)
    {
        if (empty($postId)) {
            throw new InvalidArgumentException('PostId can not be empty');
        }

        $response = $this->httpGet(sprintf('/posts/%s', $postId));

        return $this->handleResponse($response, Post::class);
    }

    /**
     * Pin a post to a channel it is in based from the provided post id string.
     *
     * @see https://api.mattermost.com/v4/#tag/posts%2Fpaths%2F~1posts~1%7Bpost_id%7D~1pin%2Fpost
     *
     * @param string $postId
     *
     * @return Status|ResponseInterface
     */
    public function pinPost($postId)
    {
        if (empty($postId)) {
            throw new InvalidArgumentException('PostId can not be empty');
        }

        $response = $this->httpPost(sprintf('/posts/%s/pin', $postId));

        return $this->handleResponse($response, Status::class);
    }

    /**
     * Unpin a post to a channel it is in based from the provided post id string.
     *
     * @see https://api.mattermost.com/v4/#tag/posts%2Fpaths%2F~1posts~1%7Bpost_id%7D~1unpin%2Fpost
     *
     * @param string $postId
     *
     * @return Status|ResponseInterface
     */
    public function unpinPost($postId)
    {
        if (empty($postId)) {
            throw new InvalidArgumentException('PostId can not be empty');
        }

        $response = $this->httpPost(sprintf('/posts/%s/unpin', $postId));

        return $this->handleResponse($response, Status::class);
    }

    /**
     * Soft deletes a post, by marking the post as deleted in the database. Soft deleted posts will not be returned in post queries.
     *
     * @see https://api.mattermost.com/v4/#tag/posts%2Fpaths%2F~1posts~1%7Bpost_id%7D%2Fdelete
     *
     * @param $postId
     *
     * @return Status|ResponseInterface
     */
    public function deletePost($postId)
    {
        if (empty($postId)) {
            throw new InvalidArgumentException('PostId can not be empty');
        }

        $response = $this->httpDelete(sprintf('/posts/%s', $postId));

        return $this->handleResponse($response, Status::class);
    }
}
