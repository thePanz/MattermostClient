<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Api;

use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Channel\Channels as ChannelsCollection;
use Pnz\MattermostClient\Model\Status;
use Pnz\MattermostClient\Model\Team\Team;
use Pnz\MattermostClient\Model\Team\TeamMember;
use Pnz\MattermostClient\Model\Team\TeamMembers;
use Pnz\MattermostClient\Model\Team\Teams as TeamsCollection;
use Pnz\MattermostClient\Model\Team\TeamStats;
use Psr\Http\Message\ResponseInterface;

final class Teams extends HttpApi
{
    /**
     * Returns an team by its ID.
     *
     * @param string $id
     *
     * @return Team|ResponseInterface
     */
    public function getTeamById($id)
    {
        if (empty($id)) {
            throw new InvalidArgumentException('Id can not be empty');
        }

        $response = $this->httpGet(sprintf('/teams/%s', $id));

        return $this->handleResponse($response, Team::class);
    }

    /**
     * Create a team. Required parameters: 'name', 'display_name' and 'type'.
     *
     * @param array $params
     *
     * @return Team|ResponseInterface
     */
    public function createTeam(array $params)
    {
        $response = $this->httpPost('/teams',
            $params
        );

        return $this->handleResponse($response, Team::class);
    }

    /**
     * Returns a collection of teams.
     *
     * @param int $page
     * @param int $perPage
     *
     * @return TeamsCollection|ResponseInterface
     */
    public function getTeams($page = 0, $perPage = 60)
    {
        $response = $this->httpGet('/teams', [
            'page' => $page,
            'per_page' => $perPage,
        ]);

        return $this->handleResponse($response, TeamsCollection::class);
    }

    /**
     * Returns a team given its name.
     *
     * @param string $name
     *
     * @return Team|ResponseInterface
     */
    public function getTeamByName($name)
    {
        $response = $this->httpGet(sprintf('/teams/name/%s', $name));

        return $this->handleResponse($response, Team::class);
    }

    /**
     * Add a user to a team, with specific roles.
     *
     * @param string $teamId
     * @param string $userId
     * @param string $roles
     * @param array  $pathParams
     *
     * @see https://api.mattermost.com/v4/#tag/teams%2Fpaths%2F~1teams~1%7Bteam_id%7D~1members%2Fpost
     *
     * @return TeamMember|ResponseInterface
     */
    public function addTeamMember($teamId, $userId, $roles = '', $pathParams = [])
    {
        $body = [
            'team_id' => $teamId,
            'user_id' => $userId,
            'roles' => $roles,
        ];

        $response = $this->httpPost(sprintf('/teams/%s/members', $teamId), $body, $pathParams);

        return $this->handleResponse($response, TeamMember::class);
    }

    /**
     * Return the team members.
     *
     * @param string $teamId The Team ID
     *
     * @see https://api.mattermost.com/v4/#tag/teams%2Fpaths%2F~1teams~1%7Bteam_id%7D~1members%2Fget
     *
     * @return TeamMembers
     */
    public function getTeamMembers(string $teamId)
    {
        if (empty($teamId)) {
            throw new InvalidArgumentException('TeamID can not be empty');
        }

        $response = $this->httpGet(sprintf('/teams/%s/members', $teamId));

        return $this->handleResponse($response, TeamMembers::class);
    }

    /**
     * Remove a team member.
     *
     * @param string $teamId The team ID
     * @param string $userId The user ID
     *
     * https://api.mattermost.com/v4/#tag/teams%2Fpaths%2F~1teams~1%7Bteam_id%7D~1members~1%7Buser_id%7D%2Fdelete
     *
     * @return Status|ResponseInterface
     */
    public function removeTeamMember(string $teamId, string $userId)
    {
        if (empty($teamId) || empty($userId)) {
            throw new InvalidArgumentException('TeamID and UserId can not be empty');
        }

        $response = $this->httpDelete(sprintf('/teams/%s/members/%s', $teamId, $userId));

        return $this->handleResponse($response, Status::class);
    }

    /**
     * Return the list of public channels in the given team.
     *
     * @param string $teamId  The team ID
     * @param int    $page    Pagination: page number
     * @param int    $perPage Pagination: channels per page
     *
     * @return ChannelsCollection|ResponseInterface
     */
    public function getTeamPublicChannels(string $teamId, int $page = 0, int $perPage = 60)
    {
        if (empty($teamId)) {
            throw new InvalidArgumentException('TeamID can not be empty');
        }

        $response = $this->httpGet(sprintf('/teams/%s/channels', $teamId), [
            'page' => $page,
            'per_page' => $perPage,
        ]);

        return $this->handleResponse($response, ChannelsCollection::class);
    }

    /**
     * Retrieve the team statistics.
     *
     * @param string $teamId The Team ID
     *
     * @return TeamStats|ResponseInterface
     */
    public function getTeamStats($teamId)
    {
        if (empty($teamId)) {
            throw new InvalidArgumentException('TeamID can not be empty');
        }

        $response = $this->httpGet(sprintf('/teams/%s/stats', $teamId));

        return $this->handleResponse($response, TeamStats::class);
    }
}
