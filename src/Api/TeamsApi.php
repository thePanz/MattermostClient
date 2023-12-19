<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Api;

use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Channel\Channels;
use Pnz\MattermostClient\Model\Status;
use Pnz\MattermostClient\Model\Team\Team;
use Pnz\MattermostClient\Model\Team\TeamMember;
use Pnz\MattermostClient\Model\Team\TeamMembers;
use Pnz\MattermostClient\Model\Team\Teams;
use Pnz\MattermostClient\Model\Team\TeamStats;

final class TeamsApi extends HttpApi
{
    /**
     * Returns an team by its ID.
     */
    public function getTeamById(string $id): Team
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
     * @param array<mixed> $data
     */
    public function createTeam(array $data): Team
    {
        $response = $this->httpPost('/teams', $data);

        return $this->handleResponse($response, Team::class);
    }

    /**
     * Returns a collection of teams.
     *
     * @param array<string, string|int> $params The listing params, 'page', 'per_page'
     */
    public function getTeams(array $params = []): Teams
    {
        $response = $this->httpGet('/teams', $params);

        return $this->handleResponse($response, Teams::class);
    }

    /**
     * Delete a team softly and put in archived only.
     *
     * @see https://api.mattermost.com/v4/#tag/teams%2Fpaths%2F~1teams~1%7Bteam_id%7D%2Fdelete
     *
     * @param string $teamId    Team GUID
     * @param bool   $permanent permanently delete the team, to be used for complience reasons only
     */
    public function deleteTeam(string $teamId, bool $permanent = false): Status
    {
        if (empty($teamId)) {
            throw new InvalidArgumentException('User ID can not be empty');
        }

        $params = $permanent ? ['permanent' => true] : [];

        $response = $this->httpDelete(sprintf('/teams/%s', $teamId), [], $params);

        return $this->handleResponse($response, Status::class);
    }

    /**
     * Returns a team given its name.
     */
    public function getTeamByName(string $name): Team
    {
        if (empty($name)) {
            throw new InvalidArgumentException('TeamName can not be empty');
        }

        $response = $this->httpGet(sprintf('/teams/name/%s', $name));

        return $this->handleResponse($response, Team::class);
    }

    /**
     * Add a user to a team, with specific roles.
     *
     * @param array<string, string|int> $params
     *
     * @see https://api.mattermost.com/v4/#tag/teams%2Fpaths%2F~1teams~1%7Bteam_id%7D~1members%2Fpost
     */
    public function addTeamMember(string $teamId, string $userId, string $roles = '', array $params = []): TeamMember
    {
        if (empty($teamId) || empty($userId)) {
            throw new InvalidArgumentException('Team ID or user ID can not be empty');
        }

        $body = [
            'team_id' => $teamId,
            'user_id' => $userId,
            'roles' => $roles,
        ];

        $response = $this->httpPost(sprintf('/teams/%s/members', $teamId), $body, $params);

        return $this->handleResponse($response, TeamMember::class);
    }

    /**
     * Return the team members.
     *
     * @param string                    $teamId The Team ID
     * @param array<string, string|int> $params The listing params, 'page', 'per_page'
     *
     * @see https://api.mattermost.com/v4/#tag/teams%2Fpaths%2F~1teams~1%7Bteam_id%7D~1members%2Fget
     */
    public function getTeamMembers(string $teamId, array $params = []): TeamMembers
    {
        if (empty($teamId)) {
            throw new InvalidArgumentException('TeamID can not be empty');
        }

        $response = $this->httpGet(sprintf('/teams/%s/members', $teamId), $params);

        return $this->handleResponse($response, TeamMembers::class);
    }

    /**
     * Get a team member from the system given a Team and User IDs.
     *
     * @param string $teamId The Team GUID
     * @param string $userId The User GUID
     */
    public function getTeamMember(string $teamId, string $userId): TeamMember
    {
        if (empty($teamId) || empty($userId)) {
            throw new InvalidArgumentException('TeamID and UserId can not be empty');
        }

        $response = $this->httpGet(sprintf('/teams/%s/members/%s', $teamId, $userId));

        return $this->handleResponse($response, TeamMember::class);
    }

    /**
     * Remove a team member.
     *
     * @param string $teamId The team ID
     * @param string $userId The user ID
     *
     * https://api.mattermost.com/v4/#tag/teams%2Fpaths%2F~1teams~1%7Bteam_id%7D~1members~1%7Buser_id%7D%2Fdelete
     */
    public function removeTeamMember(string $teamId, string $userId): Status
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
     * @param string                    $teamId The team ID
     * @param array<string, string|int> $params The listing params, 'page', 'per_page'
     */
    public function getTeamPublicChannels(string $teamId, array $params = []): Channels
    {
        if (empty($teamId)) {
            throw new InvalidArgumentException('TeamID can not be empty');
        }

        $response = $this->httpGet(sprintf('/teams/%s/channels', $teamId), $params);

        return $this->handleResponse($response, Channels::class);
    }

    /**
     * Retrieve the team statistics.
     *
     * @param string $teamId The Team ID
     */
    public function getTeamStats($teamId): TeamStats
    {
        if (empty($teamId)) {
            throw new InvalidArgumentException('TeamID can not be empty');
        }

        $response = $this->httpGet(sprintf('/teams/%s/stats', $teamId));

        return $this->handleResponse($response, TeamStats::class);
    }

    /**
     * Patch a team.
     *
     * @param array<mixed> $data
     *
     * @see https://api.mattermost.com/v4/#tag/teams%2Fpaths%2F~1teams~1%7Bteam_id%7D~1patch%2Fput
     */
    public function patchTeam(string $teamId, array $data): Team
    {
        if (empty($teamId)) {
            throw new InvalidArgumentException('TeamId can not be empty');
        }

        $response = $this->httpPut(sprintf('/teams/%s/patch', $teamId), $data);

        return $this->handleResponse($response, Team::class);
    }

    /**
     * Update a team.
     *
     * @see https://api.mattermost.com/v4/#tag/teams%2Fpaths%2F~1teams~1%7Bteam_id%7D%2Fput
     *
     * @param array<mixed> $data Required parameters are: display_name, description, company_name, allowed_domains, invite_id, allow_open_invite
     */
    public function updateTeam(string $teamId, array $data): Team
    {
        if (empty($teamId)) {
            throw new InvalidArgumentException('TeamId can not be empty');
        }

        $response = $this->httpPut(sprintf('/teams/%s', $teamId), $data);

        return $this->handleResponse($response, Team::class);
    }
}
