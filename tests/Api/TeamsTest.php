<?php

namespace Pnz\MattermostClient\Tests\Api;

use Pnz\MattermostClient\Api\Teams;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Channel\Channels;
use Pnz\MattermostClient\Model\Status;
use Pnz\MattermostClient\Model\Team\Team;
use Pnz\MattermostClient\Model\Team\TeamMembers;
use Pnz\MattermostClient\Model\Team\Teams as TeamsCollection;
use Pnz\MattermostClient\Model\Team\TeamStats;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Api\Teams
 */
class TeamsTest extends BaseHttpApiTest
{
    /**
     * @var Teams
     */
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = new Teams($this->httpClient, $this->messageFactory, $this->hydrator);
    }

    public function testGetTeamByNameSuccess()
    {
        $teamName = 'team-name';
        $this->configureMessage('GET', '/teams/name/'.$teamName);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Team::class);

        $this->client->getTeamByName($teamName);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetTeamByNameException($exception, $code)
    {
        $this->expectException($exception);
        $teamName = 'team-name';
        $this->configureMessage('GET', '/teams/name/'.$teamName);
        $this->configureRequestAndResponse($code);

        $this->client->getTeamByName($teamName);
    }

    public function testGetTeamByNameEmptyEmail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getTeamByName('');
    }

    public function testGetTeamByIdSuccess()
    {
        $teamId = '12345';
        $this->configureMessage('GET', '/teams/'.$teamId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Team::class);
        $this->client->getTeamById($teamId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetTeamByIdException($exception, $code)
    {
        $this->expectException($exception);
        $teamId = '12345';
        $this->configureMessage('GET', '/teams/'.$teamId);
        $this->configureRequestAndResponse($code);
        $this->client->getTeamById($teamId);
    }

    public function testGetTeamByIdEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getTeamById('');
    }

    public function testDeleteTeamSuccess()
    {
        $teamId = '12345';
        $this->configureMessage('DELETE', '/teams/'.$teamId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->deleteTeam($teamId);
    }

    public function testDeleteTeamPermanentSuccess()
    {
        $teamId = '12345';
        $this->configureMessage('DELETE', '/teams/'.$teamId.'?permanent=1');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->deleteTeam($teamId, true);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testDeleteTeamException($exception, $code)
    {
        $this->expectException($exception);
        $teamId = '12345';
        $this->configureMessage('DELETE', '/teams/'.$teamId);
        $this->configureRequestAndResponse($code);
        $this->client->deleteTeam($teamId);
    }

    public function testDeleteTeamEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->deleteTeam('');
    }

    public function testCreateTeamSuccess()
    {
        $data = [
            'display_name' => 'display_name',
            'invite_id' => 'invite_id,',
        ];
        $this->configureMessage('POST', '/teams', [], json_encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Team::class);

        $this->client->createTeam($data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testCreateTeamException($exception, $code)
    {
        $this->expectException($exception);
        $data = [
            'display_name' => 'display_name',
            'invite_id' => 'invite_id,',
        ];
        $this->configureMessage('POST', '/teams', [], json_encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->createTeam($data);
    }

    public function testPatchTeamSuccess()
    {
        $teamId = '111';
        $data = [
            'display_name' => 'display_name',
            'invite_id' => 'invite_id,',
        ];
        $this->configureMessage('PUT', '/teams/'.$teamId.'/patch', [], json_encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Team::class);

        $this->client->patchTeam($teamId, $data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testPatchTeamException($exception, $code)
    {
        $this->expectException($exception);
        $teamId = '111';
        $data = [
            'display_name' => 'display_name',
            'invite_id' => 'invite_id,',
        ];
        $this->configureMessage('PUT', '/teams/'.$teamId.'/patch', [], json_encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->patchTeam($teamId, $data);
    }

    public function testPatchTeamsEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->patchTeam('', []);
    }

    public function testUpdateTeamSuccess()
    {
        $teamId = '111';
        $data = [
            'username' => 'username',
            'email' => 'email,',
        ];

        $this->configureMessage('PUT', '/teams/'.$teamId, [], json_encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Team::class);
        $this->client->updateTeam($teamId, $data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testUpdateTeamException($exception, $code)
    {
        $this->expectException($exception);
        $teamId = '111';
        $data = [
            'username' => 'username',
            'email' => 'email,',
        ];

        $this->configureMessage('PUT', '/teams/'.$teamId, [], json_encode($data));
        $this->configureRequestAndResponse($code);
        $this->client->updateTeam($teamId, $data);
    }

    public function testUpdateTeamsEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->updateTeam('', []);
    }

    public function testGetTeamStatsSuccess()
    {
        $teamId = '12345';
        $this->configureMessage('GET', '/teams/'.$teamId.'/stats');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(TeamStats::class);
        $this->client->getTeamStats($teamId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetTeamStatsException($exception, $code)
    {
        $this->expectException($exception);
        $teamId = '12345';
        $this->configureMessage('GET', '/teams/'.$teamId.'/stats');
        $this->configureRequestAndResponse($code);
        $this->client->getTeamStats($teamId);
    }

    public function testGetTeamStatsEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getTeamStats('');
    }

    public function testRemoveTeamMemberSuccess()
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
     *
     * @param string $exception
     * @param int    $code
     */
    public function testRemoveTeamMemberException($exception, $code)
    {
        $this->expectException($exception);
        $teamId = '12345';
        $userId = '98765';
        $this->configureMessage('DELETE', '/teams/'.$teamId.'/members/'.$userId);
        $this->configureRequestAndResponse($code);
        $this->client->removeTeamMember($teamId, $userId);
    }

    public function testRemoveTeamMemberEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->removeTeamMember('', '');
    }

    public function testRemoveTeamMemberEmptyTeamId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->removeTeamMember('', 'user-id');
    }

    public function testRemoveTeamMemberEmptyUserId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->removeTeamMember('team-id', '');
    }

    public function testAddTeamMemberSuccess()
    {
        $teamId = '12345';
        $userId = '98765';
        $roles = 'role1, role2';

        $data = [
            'team_id' => $teamId,
            'user_id' => $userId,
            'roles' => $roles,
        ];
        $this->configureMessage('POST', '/teams/'.$teamId.'/members', [], json_encode($data));
        $this->configureRequestAndResponse(201);
        $this->client->addTeamMember($teamId, $userId, $roles);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testAddTeamMemberException($exception, $code)
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
        $this->configureMessage('POST', '/teams/'.$teamId.'/members', [], json_encode($data));
        $this->configureRequestAndResponse($code);
        $this->client->addTeamMember($teamId, $userId, $roles);
    }

    public function testAddTeamMemberEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->addTeamMember('', '');
    }

    public function testAddTeamMemberEmptyTeamId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->addTeamMember('', 'user-id');
    }

    public function testAddTeamMemberEmptyUserId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->addTeamMember('team-id', '');
    }

    public function testGetTeamMembersSuccess()
    {
        $teamId = '12345';
        $this->configureMessage('GET', '/teams/'.$teamId.'/members'.
            '?per_page=1&page=2'
        );
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(TeamMembers::class);
        $this->client->getTeamMembers($teamId, [
            'per_page' => 1,
            'page' => 2,
        ]);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetTeamMembersException($exception, $code)
    {
        $this->expectException($exception);
        $teamId = '12345';
        $this->configureMessage('GET', '/teams/'.$teamId.'/members');
        $this->configureRequestAndResponse($code);
        $this->client->getTeamMembers($teamId);
    }

    public function testGetTeamMembersEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getTeamMembers('');
    }

    public function testGetTeamPublicChannelsSuccess()
    {
        $teamId = '12345';
        $this->configureMessage('GET', '/teams/'.$teamId.'/channels'.
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
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetTeamPublicChannelsException($exception, $code)
    {
        $this->expectException($exception);
        $teamId = '12345';
        $this->configureMessage('GET', '/teams/'.$teamId.'/channels');
        $this->configureRequestAndResponse($code);
        $this->client->getTeamPublicChannels($teamId);
    }

    public function testGetTeamPublicChannelsEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getTeamPublicChannels('');
    }

    public function testGetTeamsSuccess()
    {
        $this->configureMessage('GET', '/teams'.
            '?per_page=1&page=2'
        );
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(TeamsCollection::class);
        $this->client->getTeams([
            'per_page' => 1,
            'page' => 2,
        ]);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetTeamsException($exception, $code)
    {
        $this->expectException($exception);
        $this->configureMessage('GET', '/teams');
        $this->configureRequestAndResponse($code);
        $this->client->getTeams();
    }
}
