<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Api;

use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Channel\Channel;
use Pnz\MattermostClient\Model\Channel\ChannelMember;
use Pnz\MattermostClient\Model\Channel\ChannelMembers;
use Pnz\MattermostClient\Model\Channel\ChannelStats;
use Pnz\MattermostClient\Model\Post\Posts;
use Pnz\MattermostClient\Model\Status;
use Psr\Http\Message\ResponseInterface;

final class ChannelsApi extends HttpApi
{
    /**
     * Returns an channel by its ID.
     *
     * @return Channel|ResponseInterface
     */
    public function getChannelById(string $channelId)
    {
        if (empty($channelId)) {
            throw new InvalidArgumentException('Id can not be empty');
        }

        $response = $this->httpGet(sprintf('/channels/%s', $channelId));

        return $this->handleResponse($response, Channel::class);
    }

    /**
     * Returns a channel given the team ID and the channel name.
     *
     * @param array $parameters Associative array of additional parameters for the request:
     *                          - 'include_deleted'=>'true': allow to fetch deleted channels too
     *
     * @return Channel|ResponseInterface
     */
    public function getChannelByName(string $teamId, string $channelName, array $parameters = [])
    {
        if (empty($teamId) || empty($channelName)) {
            throw new InvalidArgumentException('Team ID and channel name can not be empty');
        }

        $response = $this->httpGet(
            sprintf('/teams/%s/channels/name/%s', $teamId, $channelName),
            $parameters
        );

        return $this->handleResponse($response, Channel::class);
    }

    /**
     * Returns an channel by the team name and the chanel name.
     *
     * @return Channel|ResponseInterface
     */
    public function getChannelByNameAndTeamName(string $teamName, string $channelName)
    {
        if (empty($teamName) || empty($channelName)) {
            throw new InvalidArgumentException('Team ID and channel name can not be empty');
        }

        $response = $this->httpGet(sprintf('/teams/name/%s/channels/name/%s', $teamName, $channelName));

        return $this->handleResponse($response, Channel::class);
    }

    /**
     * Retrieve the channel statistics of the given channelId.
     *
     * @return ChannelStats|ResponseInterface
     */
    public function getChannelStats(string $channelId)
    {
        if (empty($channelId)) {
            throw new InvalidArgumentException('Channel ID can not be empty');
        }

        $response = $this->httpGet(sprintf('/channels/%s/stats', $channelId));

        return $this->handleResponse($response, ChannelStats::class);
    }

    /**
     * Create a Channel. Required parameters: 'team_id', 'name', 'display_name' and 'type'.
     *
     * @return Channel|ResponseInterface
     */
    public function createChannel(array $params)
    {
        $response = $this->httpPost('/channels', $params);

        return $this->handleResponse($response, Channel::class);
    }

    /**
     * Creates and returns a direct channel between two users.
     *
     * @return Channel|ResponseInterface
     */
    public function createDirectChannel(string $userId1, string $userId2)
    {
        if (empty($userId1) || empty($userId2)) {
            throw new InvalidArgumentException('Two user IDs must be provided');
        }

        $response = $this->httpPost('/channels/direct', [$userId1, $userId2]);

        return $this->handleResponse($response, Channel::class);
    }

    /**
     * Delete a Channel.
     *
     * @see https://api.mattermost.com/v4/#tag/channels%2Fpaths%2F~1channels~1%7Bchannel_id%7D%2Fdelete
     *
     * @param array $params Query parameters
     *  - permanent => "true"|"false" (Mattermost >= 5.28)
     *
     * @return Status|ResponseInterface
     */
    public function deleteChannel(string $channelId, array $params = [])
    {
        if (empty($channelId)) {
            throw new InvalidArgumentException('Channel ID can not be empty');
        }

        $response = $this->httpDelete(sprintf('/channels/%s', $channelId), [], $params);

        return $this->handleResponse($response, Status::class);
    }

    /**
     * Restore channel from the provided channel id string.
     *
     * @see https://api.mattermost.com/v4/#tag/channels%2Fpaths%2F~1channels~1%7Bchannel_id%7D~1restore%2Fpost
     *
     * @return Channel|ResponseInterface
     */
    public function restoreChannel(string $channelId)
    {
        if (empty($channelId)) {
            throw new InvalidArgumentException('ChannelId can not be empty');
        }

        $response = $this->httpPost(sprintf('/channels/%s/restore', $channelId));

        return $this->handleResponse($response, Channel::class);
    }

    /**
     * Patch a channel.
     *
     * @see https://api.mattermost.com/v4/#tag/channels%2Fpaths%2F~1channels~1%7Bchannel_id%7D~1patch%2Fput
     *
     * @return Channel|ResponseInterface
     */
    public function patchChannel(string $channelId, array $params)
    {
        if (empty($channelId)) {
            throw new InvalidArgumentException('ChannelId can not be empty');
        }

        $response = $this->httpPut(sprintf('/channels/%s/patch', $channelId), $params);

        return $this->handleResponse($response, Channel::class);
    }

    /**
     * Update a channel.
     *
     * @see https://api.mattermost.com/v4/#tag/channels%2Fpaths%2F~1channels~1%7Bchannel_id%7D%2Fput
     *
     * @return Channel|ResponseInterface
     */
    public function updateChannel(string $channelId, array $params)
    {
        if (empty($channelId)) {
            throw new InvalidArgumentException('ChannelId can not be empty');
        }

        $response = $this->httpPut(sprintf('/channels/%s', $channelId), $params);

        return $this->handleResponse($response, Channel::class);
    }

    /**
     * Add a user to a channel.
     *
     * @see https://api.mattermost.com/v4/#tag/channels%2Fpaths%2F~1channels~1%7Bchannel_id%7D~1members%2Fpost
     *
     * @return ChannelMember|ResponseInterface
     */
    public function addChannelMember(string $channelId, string $userId, string $postRootId = '')
    {
        if (empty($channelId) || empty($userId)) {
            throw new InvalidArgumentException('Channel ID or user ID can not be empty');
        }

        $body = [
            'channel_id' => $channelId,
            'user_id' => $userId,
            'post_root_id' => $postRootId,
        ];

        $response = $this->httpPost(sprintf('/channels/%s/members', $channelId), $body);

        return $this->handleResponse($response, ChannelMember::class);
    }

    /**
     * Remove a user from a channel.
     *
     * @return Status|ResponseInterface
     */
    public function removeChannelMember(string $channelId, string $userId)
    {
        if (empty($channelId) || empty($userId)) {
            throw new InvalidArgumentException('Channel ID or user ID can not be empty');
        }

        $response = $this->httpDelete(sprintf('/channels/%s/members/%s', $channelId, $userId));

        return $this->handleResponse($response, Status::class);
    }

    /**
     * Get members of a channel.
     *
     * @param array $params The listing params, 'page', 'per_page'
     *
     * @return ChannelMembers|ResponseInterface
     */
    public function getChannelMembers(string $channelId, array $params = [])
    {
        if (empty($channelId)) {
            throw new InvalidArgumentException('ChannelID can not be empty');
        }

        $response = $this->httpGet(sprintf('/channels/%s/members', $channelId), $params);

        return $this->handleResponse($response, ChannelMembers::class);
    }

    /**
     * Get the posts for a channel.
     *
     * @param array $params The listing params: 'page', 'per_page', 'before', 'after', 'since'
     *
     * @see: https://api.mattermost.com/v4/#tag/posts%2Fpaths%2F~1channels~1%7Bchannel_id%7D~1posts%2Fget
     *
     * @return Posts|ResponseInterface
     */
    public function getChannelPosts(string $channelId, array $params = [])
    {
        if (empty($channelId)) {
            throw new InvalidArgumentException('ChannelID can not be empty');
        }

        $response = $this->httpGet(sprintf('/channels/%s/posts', $channelId), $params);

        return $this->handleResponse($response, Posts::class);
    }
}
