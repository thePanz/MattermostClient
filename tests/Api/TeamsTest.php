<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Pnz\MattermostClient\Api\TeamsApi;
use Pnz\MattermostClient\Exception\DomainException;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Channel\Channels;
use Pnz\MattermostClient\Model\Error;
use Pnz\MattermostClient\Model\Status;
use Pnz\MattermostClient\Model\Team\Team;
use Pnz\MattermostClient\Model\Team\TeamMember;
use Pnz\MattermostClient\Model\Team\TeamMembers;
use Pnz\MattermostClient\Model\Team\Teams;
use Pnz\MattermostClient\Model\Team\TeamStats;

/**
 * @internal
 */
#[CoversClass(TeamsApi::class)]
final class TeamsTest extends AbstractHttpApiTestCase
{
    private TeamsApi $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new TeamsApi($this->httpClient, $this->psr17factory, $this->psr17factory, $this->hydrator);
    }

    public function testGetTeamByNameSucceeds(): void
    {
        $response = $this->buildResponse(200);

        $this->expectRequest('GET', '/teams/name/'.self::TEAM_NAME, [], $response);
        $this->expectHydration($response, Team::class);

        $this->client->getTeamByName(self::TEAM_NAME);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetTeamByNameThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('GET', '/teams/name/'.self::TEAM_NAME, [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getTeamByName(self::TEAM_NAME);
    }

    public function testGetTeamByNameEmptyEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->client->getTeamByName('');
    }

    public function testGetTeamByIdSucceeds(): void
    {
        $response = $this->buildResponse(200);

        $this->expectRequest('GET', '/teams/'.self::TEAM_ID, [], $response);
        $this->expectHydration($response, Team::class);

        $this->client->getTeamById(self::TEAM_ID);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetTeamByIdThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('GET', '/teams/'.self::TEAM_ID, [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getTeamById(self::TEAM_ID);
    }

    public function testGetTeamByIdEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->client->getTeamById('');
    }

    public function testDeleteTeamSucceeds(): void
    {
        $response = $this->buildResponse(200);

        $this->expectRequest('DELETE', '/teams/'.self::TEAM_ID, [], $response);
        $this->expectHydration($response, Status::class);

        $this->client->deleteTeam(self::TEAM_ID);
    }

    public function testDeleteTeamPermanentSucceeds(): void
    {
        $response = $this->buildResponse(200);

        $this->expectRequest('DELETE', '/teams/'.self::TEAM_ID.'?permanent=1', [], $response);
        $this->expectHydration($response, Status::class);

        $this->client->deleteTeam(self::TEAM_ID, true);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testDeleteTeamThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('DELETE', '/teams/'.self::TEAM_ID, [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->deleteTeam(self::TEAM_ID);
    }

    public function testDeleteTeamEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->deleteTeam('');
    }

    public function testCreateTeamSucceeds(): void
    {
        $requestData = ['display_name' => 'display_name', 'invite_id' => self::TEAM_INVITE_ID];
        $response = $this->buildResponse(201);

        $this->expectRequest('POST', '/teams', $requestData, $response);
        $this->expectHydration($response, Team::class);

        $this->client->createTeam($requestData);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testCreateTeamThrows(string $exception, int $code): void
    {
        $requestData = ['display_name' => 'display_name', 'invite_id' => self::TEAM_INVITE_ID];
        $response = $this->buildResponse($code);

        $this->expectRequest('POST', '/teams', $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->createTeam($requestData);
    }

    public function testPatchTeamSucceeds(): void
    {
        $requestData = ['display_name' => 'display_name', 'invite_id' => self::TEAM_INVITE_ID];
        $response = $this->buildResponse(201);

        $this->expectRequest('PUT', '/teams/'.self::TEAM_ID.'/patch', $requestData, $response);
        $this->expectHydration($response, Team::class);

        $this->client->patchTeam(self::TEAM_ID, $requestData);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testPatchTeamThrows(string $exception, int $code): void
    {
        $requestData = ['display_name' => 'display_name', 'invite_id' => self::TEAM_INVITE_ID];
        $response = $this->buildResponse($code);

        $this->expectRequest('PUT', '/teams/'.self::TEAM_ID.'/patch', $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->patchTeam(self::TEAM_ID, $requestData);
    }

    public function testPatchTeamsEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->client->patchTeam('', []);
    }

    public function testUpdateTeamSucceeds(): void
    {
        $requestData = ['username' => self::USER_USERNAME, 'email' => self::USER_EMAIL];

        $response = $this->buildResponse(201);

        $this->expectRequest('PUT', '/teams/'.self::TEAM_ID, $requestData, $response);
        $this->expectHydration($response, Team::class);

        $this->client->updateTeam(self::TEAM_ID, $requestData);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testUpdateTeamThrows(string $exception, int $code): void
    {
        $requestData = ['username' => self::USER_USERNAME, 'email' => self::USER_EMAIL];
        $response = $this->buildResponse($code);

        $this->expectRequest('PUT', '/teams/'.self::TEAM_ID, $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->updateTeam(self::TEAM_ID, $requestData);
    }

    public function testUpdateTeamsEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->client->updateTeam('', []);
    }

    public function testGetTeamStatsSucceeds(): void
    {
        $response = $this->buildResponse(200);

        $this->expectRequest('GET', '/teams/'.self::TEAM_ID.'/stats', [], $response);
        $this->expectHydration($response, TeamStats::class);

        $this->client->getTeamStats(self::TEAM_ID);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetTeamStatsThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('GET', '/teams/'.self::TEAM_ID.'/stats', [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getTeamStats(self::TEAM_ID);
    }

    public function testGetTeamStatsEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->client->getTeamStats('');
    }

    public function testRemoveTeamMemberSucceeds(): void
    {
        $response = $this->buildResponse(200);

        $this->expectRequest('DELETE', '/teams/'.self::TEAM_ID.'/members/'.self::USER_UUID, [], $response);
        $this->expectHydration($response, Status::class);

        $this->client->removeTeamMember(self::TEAM_ID, self::USER_UUID);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testRemoveTeamMemberThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('DELETE', '/teams/'.self::TEAM_ID.'/members/'.self::USER_UUID, [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->removeTeamMember(self::TEAM_ID, self::USER_UUID);
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

    public function testGetTeamMemberSucceeds(): void
    {
        $responseData = [];
        $response = $this->buildResponse(200, $responseData);

        $this->expectRequest('GET', '/teams/'.self::TEAM_ID.'/members/'.self::USER_UUID, [], $response);
        $this->expectHydration($response, TeamMember::class);

        $this->client->getTeamMember(self::TEAM_ID, self::USER_UUID);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetTeamMemberThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('GET', '/teams/'.self::TEAM_ID.'/members/'.self::USER_UUID, [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getTeamMember(self::TEAM_ID, self::USER_UUID);
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

    public function testAddTeamMemberSucceeds(): void
    {
        $roles = 'role1, role2';
        $requestData = ['team_id' => self::TEAM_ID, 'user_id' => self::USER_UUID, 'roles' => $roles];
        $response = $this->buildResponse(201);

        $this->expectRequest('POST', '/teams/'.self::TEAM_ID.'/members', $requestData, $response);
        $this->expectHydration($response, TeamMember::class);

        $this->client->addTeamMember(self::TEAM_ID, self::USER_UUID, $roles);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testAddTeamMemberThrows(string $exception, int $code): void
    {
        $roles = 'role1, role2';
        $requestData = ['team_id' => self::TEAM_ID, 'user_id' => self::USER_UUID, 'roles' => $roles];
        $response = $this->buildResponse($code);

        $this->expectRequest('POST', '/teams/'.self::TEAM_ID.'/members', $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->addTeamMember(self::TEAM_ID, self::USER_UUID, $roles);
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

    public function testGetTeamMembersSucceeds(): void
    {
        $response = $this->buildResponse(200);

        $this->expectRequest('GET', '/teams/'.self::TEAM_ID.'/members?per_page=1&page=2', [], $response);
        $this->expectHydration($response, TeamMembers::class);

        $this->client->getTeamMembers(self::TEAM_ID, ['per_page' => 1, 'page' => 2]);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetTeamMembersThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('GET', '/teams/'.self::TEAM_ID.'/members', [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getTeamMembers(self::TEAM_ID);
    }

    public function testGetTeamMembersEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->client->getTeamMembers('');
    }

    public function testGetTeamPublicChannelsSucceeds(): void
    {
        $response = $this->buildResponse(200);

        $this->expectRequest(
            'GET',
            '/teams/'.self::TEAM_ID.'/channels?per_page=1&page=2',
            [],
            $response
        );
        $this->expectHydration($response, Channels::class);

        $this->client->getTeamPublicChannels(self::TEAM_ID, [
            'per_page' => 1,
            'page' => 2,
        ]);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetTeamPublicChannelsThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('GET', '/teams/'.self::TEAM_ID.'/channels', [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getTeamPublicChannels(self::TEAM_ID);
    }

    public function testGetTeamPublicChannelsEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->client->getTeamPublicChannels('');
    }

    public function testGetTeamsSucceeds(): void
    {
        $response = $this->buildResponse(200);

        $this->expectRequest('GET', '/teams?per_page=1&page=2', [], $response);
        $this->expectHydration($response, Teams::class);

        $this->client->getTeams(['per_page' => 1, 'page' => 2]);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetTeamsThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('GET', '/teams', [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getTeams();
    }
}
