<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Api;

use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Post\Post;
use Pnz\MattermostClient\Model\Status;

final class PostsApi extends HttpApi
{
    /**
     * @param array<mixed> $data
     *
     * Create a post. Required parameters: 'channel_id', 'message'.
     */
    public function createPost(array $data): Post
    {
        $response = $this->httpPost('/posts', $data);

        return $this->handleResponse($response, Post::class);
    }

    /**
     * Partially update a post by providing only the fields you want to update.
     * Omitted fields will not be updated.
     *
     * @param array<mixed> $data
     *
     * @see https://api.mattermost.com/v4/#tag/posts%2Fpaths%2F~1posts~1%7Bpost_id%7D~1patch%2Fput
     */
    public function patchPost(string $postId, array $data): Post
    {
        if (empty($postId)) {
            throw new InvalidArgumentException('PostId can not be empty');
        }

        $response = $this->httpPut(sprintf('/posts/%s/patch', $postId), $data);

        return $this->handleResponse($response, Post::class);
    }

    /**
     * Update a post.
     *
     * @see https://api.mattermost.com/v4/#tag/posts%2Fpaths%2F~1posts~1%7Bpost_id%7D%2Fput
     *
     * @param array<mixed> $data
     * @param string       $postId ID of the post to update
     */
    public function updatePost(string $postId, array $data): Post
    {
        if (empty($postId)) {
            throw new InvalidArgumentException('PostId can not be empty');
        }

        $response = $this->httpPut(sprintf('/posts/%s', $postId), $data);

        return $this->handleResponse($response, Post::class);
    }

    /**
     * Get a single post.
     *
     * @param string $postId ID of the post to get
     */
    public function getPost(string $postId): Post
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
     * @param string $postId Post GUID
     */
    public function pinPost(string $postId): Status
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
     * @param string $postId Post GUID
     */
    public function unpinPost(string $postId): Status
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
     * @param string $postId ID of the post to delete
     */
    public function deletePost(string $postId): Status
    {
        if (empty($postId)) {
            throw new InvalidArgumentException('PostId can not be empty');
        }

        $response = $this->httpDelete(sprintf('/posts/%s', $postId));

        return $this->handleResponse($response, Status::class);
    }
}
