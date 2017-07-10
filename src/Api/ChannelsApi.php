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
     * @param string $channelId
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
     * Returns an channel by its ID.
     *
     * @param string $teamId
     * @param string $channelName
     *
     * @return Channel|ResponseInterface
     */
    public function getChannelByName(string $teamId, string $channelName)
    {
        if (empty($teamId) || empty($channelName)) {
            throw new InvalidArgumentException('Team ID and channel name can not be empty');
        }

        $response = $this->httpGet(sprintf('/teams/%s/channels/name/%s', $teamId, $channelName));

        return $this->handleResponse($response, Channel::class);
    }

    /**
     * Returns an channel by its name.
     *
     * @param string $teamName
     * @param string $channelName
     *
     * @return Channel|ResponseInterface
     */
    public function getChannelByNameAndTeamName($teamName, $channelName)
    {
        if (empty($teamName) || empty($channelName)) {
            throw new InvalidArgumentException('Team ID and channel name can not be empty');
        }

        $response = $this->httpGet(sprintf('/teams/name/%s/channels/name/%s', $teamName, $channelName));

        return $this->handleResponse($response, Channel::class);
    }

    /**
     * Retrieve the channel statistics.
     *
     * @param string $channelId The Team ID
     *
     * @return ChannelStats|ResponseInterface
     */
    public function getChannelStats($channelId)
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
     * @param array $params
     *
     * @return Channel|ResponseInterface
     */
    public function createChannel(array $params)
    {
        $response = $this->httpPost('/channels', $params);

        return $this->handleResponse($response, Channel::class);
    }

    /**
     * Delete a Channel.
     *
     * @see https://api.mattermost.com/v4/#tag/channels%2Fpaths%2F~1channels~1%7Bchannel_id%7D%2Fdelete
     *
     * @param string $channelId
     *
     * @return Status
     */
    public function deleteChannel($channelId)
    {
        if (empty($channelId)) {
            throw new InvalidArgumentException('Channel ID can not be empty');
        }

        $response = $this->httpDelete(sprintf('/channels/%s', $channelId));

        return $this->handleResponse($response, Status::class);
    }

    /**
     * Restore channel from the provided channel id string.
     *
     * @see https://api.mattermost.com/v4/#tag/channels%2Fpaths%2F~1channels~1%7Bchannel_id%7D~1restore%2Fpost
     *
     * @param string $channelId
     *
     * @return Channel|ResponseInterface
     */
    public function restoreChannel($channelId)
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
     * @param string $channelId
     * @param array  $params
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
     * @param string $channelId
     * @param array  $params
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
     * Add a user to a channel, with specific roles.
     *
     * @param string $channelId
     * @param string $userId
     * @param string $roles
     *
     * @return ChannelMember|ResponseInterface
     */
    public function addChannelMember(string $channelId, string $userId, string $roles = '')
    {
        if (empty($channelId) || empty($userId)) {
            throw new InvalidArgumentException('Channel ID or user ID can not be empty');
        }

        $body = [
            'channel_id' => $channelId,
            'user_id' => $userId,
            'roles' => $roles,
        ];

        $response = $this->httpPost(sprintf('/channels/%s/members', $channelId), $body);

        return $this->handleResponse($response, ChannelMember::class);
    }

    /**
     * Remove a user from a channel.
     *
     * @param string $channelId
     * @param string $userId
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
     * @param string $channelId
     * @param array  $params    The listing params, 'page', 'per_page'
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
     * @param string $channelId
     * @param array  $params    The listing params: 'page', 'per_page', 'before', 'after', 'since'
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
