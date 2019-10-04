<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Api;

use Pnz\JsonException\Json;
use Pnz\MattermostClient\Api\TeamsApi;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Channel\Channels;
use Pnz\MattermostClient\Model\Status;
use Pnz\MattermostClient\Model\Team\Team;
use Pnz\MattermostClient\Model\Team\TeamMember;
use Pnz\MattermostClient\Model\Team\TeamMembers;
use Pnz\MattermostClient\Model\Team\Teams;
use Pnz\MattermostClient\Model\Team\TeamStats;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Api\TeamsApi
 */
class TeamsTest extends BaseHttpApiTest
{
    /**
     * @var TeamsApi
     */
    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new TeamsApi($this->httpClient, $this->requestFactory, $this->hydrator);
    }

    public function testGetTeamByNameSuccess(): void
    {
        $teamName = 'team-name';
        $this->configureMessage('GET', '/teams/name/'.$teamName);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Team::class);

        $this->client->getTeamByName($teamName);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetTeamByNameException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $teamName = 'team-name';
        $this->configureMessage('GET', '/teams/name/'.$teamName);
        $this->configureRequestAndResponse($code);

        $this->client->getTeamByName($teamName);
    }

    public function testGetTeamByNameEmptyEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getTeamByName('');
    }

    public function testGetTeamByIdSuccess(): void
    {
        $teamId = '12345';
        $this->configureMessage('GET', '/teams/'.$teamId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Team::class);
        $this->client->getTeamById($teamId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetTeamByIdException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $teamId = '12345';
        $this->configureMessage('GET', '/teams/'.$teamId);
        $this->configureRequestAndResponse($code);
        $this->client->getTeamById($teamId);
    }

    public function testGetTeamByIdEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getTeamById('');
    }

    public function testDeleteTeamSuccess(): void
    {
        $teamId = '12345';
        $this->configureMessage('DELETE', '/teams/'.$teamId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->deleteTeam($teamId);
    }

    public function testDeleteTeamPermanentSuccess(): void
    {
        $teamId = '12345';
        $this->configureMessage('DELETE', '/teams/'.$teamId.'?permanent=1');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->deleteTeam($teamId, true);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testDeleteTeamException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $teamId = '12345';
        $this->configureMessage('DELETE', '/teams/'.$teamId);
        $this->configureRequestAndResponse($code);
        $this->client->deleteTeam($teamId);
    }

    public function testDeleteTeamEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->deleteTeam('');
    }

    public function testCreateTeamSuccess(): void
    {
        $data = [
            'display_name' => 'display_name',
            'invite_id' => 'invite_id,',
        ];
        $this->configureMessage('POST', '/teams', [], Json::encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Team::class);

        $this->client->createTeam($data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testCreateTeamException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $data = [
            'display_name' => 'display_name',
            'invite_id' => 'invite_id,',
        ];
        $this->configureMessage('POST', '/teams', [], Json::encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->createTeam($data);
    }

    public function testPatchTeamSuccess(): void
    {
        $teamId = '111';
        $data = [
            'display_name' => 'display_name',
            'invite_id' => 'invite_id,',
        ];
        $this->configureMessage('PUT', '/teams/'.$teamId.'/patch', [], Json::encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Team::class);

        $this->client->patchTeam($teamId, $data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testPatchTeamException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $teamId = '111';
        $data = [
            'display_name' => 'display_name',
            'invite_id' => 'invite_id,',
        ];
        $this->configureMessage('PUT', '/teams/'.$teamId.'/patch', [], Json::encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->patchTeam($teamId, $data);
    }

    public function testPatchTeamsEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->patchTeam('', []);
    }

    public function testUpdateTeamSuccess(): void
    {
        $teamId = '111';
        $data = [
            'username' => 'username',
            'email' => 'email,',
        ];

        $this->configureMessage('PUT', '/teams/'.$teamId, [], Json::encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Team::class);
        $this->client->updateTeam($teamId, $data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testUpdateTeamException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $teamId = '111';
        $data = [
            'username' => 'username',
            'email' => 'email,',
        ];

        $this->configureMessage('PUT', '/teams/'.$teamId, [], Json::encode($data));
        $this->configureRequestAndResponse($code);
        $this->client->updateTeam($teamId, $data);
    }

    public function testUpdateTeamsEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->updateTeam('', []);
    }

    public function testGetTeamStatsSuccess(): void
    {
        $teamId = '12345';
        $this->configureMessage('GET', '/teams/'.$teamId.'/stats');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(TeamStats::class);
        $this->client->getTeamStats($teamId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetTeamStatsException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $teamId = '12345';
        $this->configureMessage('GET', '/teams/'.$teamId.'/stats');
        $this->configureRequestAndResponse($code);
        $this->client->getTeamStats($teamId);
    }

    public function testGetTeamStatsEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getTeamStats('');
    }

    public function testRemoveTeamMemberSuccess(): void
    {
        $teamId = '12345';
        $userId = '98765';
        $this->configureMessage('DELETE', '/teams/'.$teamId.'/members/'.$userId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->removeTeamMember($teamId, $userId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testRemoveTeamMemberException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $teamId = '12345';
        $userId = '98765';
        $this->configureMessage('DELETE', '/teams/'.$teamId.'/members/'.$userId);
        $this->configureRequestAndResponse($code);
        $this->client->removeTeamMember($teamId, $userId);
    }

    public function testRemoveTeamMemberEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->removeTeamMember('', '');
    }

    public function testRemoveTeamMemberEmptyTeamId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->removeTeamMember('', 'user-id');
    }

    public function testRemoveTeamMemberEmptyUserId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->removeTeamMember('team-id', '');
    }

    public function testGetTeamMemberSuccess(): void
    {
        $teamId = '12345';
        $userId = '98765';
        $this->configureMessage('GET', '/teams/'.$teamId.'/members/'.$userId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(TeamMember::class, TeamMember::createFromArray([]));
        $this->client->getTeamMember($teamId, $userId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetTeamMemberException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $teamId = '12345';
        $userId = '98765';
        $this->configureMessage('GET', '/teams/'.$teamId.'/members/'.$userId);
        $this->configureRequestAndResponse($code);
        $this->client->getTeamMember($teamId, $userId);
    }

    public function testGetTeamMemberEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getTeamMember('', '');
    }

    public function testGetTeamMemberEmptyTeamId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getTeamMember('', 'user-id');
    }

    public function testGetTeamMemberEmptyUserId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getTeamMember('team-id', '');
    }

    public function testAddTeamMemberSuccess(): void
    {
        $teamId = '12345';
        $userId = '98765';
        $roles = 'role1, role2';

        $data = [
            'team_id' => $teamId,
            'user_id' => $userId,
            'roles' => $roles,
        ];
        $this->configureMessage('POST', '/teams/'.$teamId.'/members', [], Json::encode($data));
        $this->configureRequestAndResponse(201);
        $this->client->addTeamMember($teamId, $userId, $roles);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testAddTeamMemberException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $teamId = '12345';
        $userId = '98765';
        $roles = 'role1, role2';

        $data = [
            'team_id' => $teamId,
            'user_id' => $userId,
            'roles' => $roles,
        ];
        $this->configureMessage('POST', '/teams/'.$teamId.'/members', [], Json::encode($data));
        $this->configureRequestAndResponse($code);
        $this->client->addTeamMember($teamId, $userId, $roles);
    }

    public function testAddTeamMemberEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->addTeamMember('', '');
    }

    public function testAddTeamMemberEmptyTeamId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->addTeamMember('', 'user-id');
    }

    public function testAddTeamMemberEmptyUserId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->addTeamMember('team-id', '');
    }

    public function testGetTeamMembersSuccess(): void
    {
        $teamId = '12345';
        $this->configureMessage(
            'GET',
            '/teams/'.$teamId.'/members'.
            '?per_page=1&page=2'
        );
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(TeamMembers::class, TeamMembers::createFromArray([]));
        $this->client->getTeamMembers($teamId, [
            'per_page' => 1,
            'page' => 2,
        ]);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetTeamMembersException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $teamId = '12345';
        $this->configureMessage('GET', '/teams/'.$teamId.'/members');
        $this->configureRequestAndResponse($code);
        $this->client->getTeamMembers($teamId);
    }

    public function testGetTeamMembersEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getTeamMembers('');
    }

    public function testGetTeamPublicChannelsSuccess(): void
    {
        $teamId = '12345';
        $this->configureMessage(
            'GET',
            '/teams/'.$teamId.'/channels'.
            '?per_page=1&page=2'
        );
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Channels::class);
        $this->client->getTeamPublicChannels($teamId, [
            'per_page' => 1,
            'page' => 2,
        ]);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetTeamPublicChannelsException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $teamId = '12345';
        $this->configureMessage('GET', '/teams/'.$teamId.'/channels');
        $this->configureRequestAndResponse($code);
        $this->client->getTeamPublicChannels($teamId);
    }

    public function testGetTeamPublicChannelsEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getTeamPublicChannels('');
    }

    public function testGetTeamsSuccess(): void
    {
        $this->configureMessage(
            'GET',
            '/teams'.
            '?per_page=1&page=2'
        );
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Teams::class);
        $this->client->getTeams([
            'per_page' => 1,
            'page' => 2,
        ]);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetTeamsException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $this->configureMessage('GET', '/teams');
        $this->configureRequestAndResponse($code);
        $this->client->getTeams();
    }
}
