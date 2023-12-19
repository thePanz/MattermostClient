<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Pnz\MattermostClient\Api\ChannelsApi;
use Pnz\MattermostClient\Exception\DomainException;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Channel\Channel;
use Pnz\MattermostClient\Model\Channel\ChannelMember;
use Pnz\MattermostClient\Model\Channel\ChannelMembers;
use Pnz\MattermostClient\Model\Channel\ChannelStats;
use Pnz\MattermostClient\Model\Error;
use Pnz\MattermostClient\Model\Post\Posts;
use Pnz\MattermostClient\Model\Status;

/**
 * @internal
 */
#[CoversClass(ChannelsApi::class)]
final class ChannelsTest extends AbstractHttpApiTestCase
{
    private ChannelsApi $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new ChannelsApi($this->httpClient, $this->psr17factory, $this->psr17factory, $this->hydrator);
    }

    public function testCreateChannelSucceeds(): void
    {
        $requestData = ['name' => 'name', 'display_name' => 'display_name', 'team_id' => '12345', 'type' => 'P'];
        $responseData = ['name' => 'name', 'id' => self::CHANNEL_UUID];

        $response = $this->buildResponse(201, $responseData);
        $this->expectRequest('POST', '/channels', $requestData, $response);
        $this->expectHydration($response, Channel::class);

        $c = $this->client->createChannel($requestData);
        $this->assertSame(self::CHANNEL_UUID, $c->getId());
        $this->assertSame('name', $c->getName());
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testCreateChannelThrows(string $exception, int $code): void
    {
        $requestData = ['name' => 'name', 'display_name' => 'display_name', 'team_id' => '12345', 'type' => 'P'];
        $response = $this->buildResponse($code);

        $this->expectRequest('POST', '/channels', $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->createChannel($requestData);
    }

    public function testCreateDirectChannelSucceeds(): void
    {
        $requestData = ['uid1', 'uid2'];
        $responseData = ['name' => 'name', 'id' => self::CHANNEL_UUID];

        $response = $this->buildResponse(201, $responseData);
        $this->expectRequest('POST', '/channels/direct', $requestData, $response);
        $this->expectHydration($response, Channel::class);

        $c = $this->client->createDirectChannel('uid1', 'uid2');
        $this->assertSame(self::CHANNEL_UUID, $c->getId());
        $this->assertSame('name', $c->getName());
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testCreateDirectChannelThrows(string $exception, int $code): void
    {
        $requestData = ['uid1', 'uid2'];

        $response = $this->buildResponse($code);
        $this->expectRequest('POST', '/channels/direct', $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->createDirectChannel('uid1', 'uid2');
    }

    public function testCreateDirectChannelWithEmptyUserIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->createDirectChannel('uid1', '');
    }

    public function testUpdateChannelSucceeds(): void
    {
        $requestData = ['name' => 'name'];
        $responseData = ['name' => 'name', 'id' => self::CHANNEL_UUID];

        $response = $this->buildResponse(201, $responseData);
        $this->expectRequest('PUT', '/channels/'.self::CHANNEL_UUID, $requestData, $response);
        $this->expectHydration($response, Channel::class);
        $c = $this->client->updateChannel(self::CHANNEL_UUID, $requestData);

        $this->assertSame(self::CHANNEL_UUID, $c->getId());
    }

    public function testUpdateChannelWithEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->updateChannel('', []);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testUpdateChannelThrows(string $exception, int $code): void
    {
        $requestData = ['name' => 'name'];

        $response = $this->buildResponse($code);
        $this->expectRequest('PUT', '/channels/'.self::CHANNEL_UUID, $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->updateChannel(self::CHANNEL_UUID, $requestData);
    }

    public function testPatchChannelSucceeds(): void
    {
        $requestData = ['name' => 'name'];
        $responseData = ['name' => 'name', 'id' => self::CHANNEL_UUID];

        $response = $this->buildResponse(201, $responseData);
        $this->expectRequest('PUT', '/channels/'.self::CHANNEL_UUID.'/patch', $requestData, $response);
        $this->expectHydration($response, Channel::class);
        $c = $this->client->patchChannel(self::CHANNEL_UUID, $requestData);

        $this->assertSame(self::CHANNEL_UUID, $c->getId());
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testPatchChannelThrows(string $exception, int $code): void
    {
        $requestData = ['name' => 'name'];

        $response = $this->buildResponse($code);
        $this->expectRequest('PUT', '/channels/'.self::CHANNEL_UUID.'/patch', $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->patchChannel(self::CHANNEL_UUID, $requestData);
    }

    public function testPatchChannelWithEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->patchChannel('', []);
    }

    public function testGetChannelByIdSucceeds(): void
    {
        $responseData = ['name' => 'name', 'id' => self::CHANNEL_UUID];

        $response = $this->buildResponse(200, $responseData);
        $this->expectRequest('GET', '/channels/'.self::CHANNEL_UUID, [], $response);
        $this->expectHydration($response, Channel::class);
        $c = $this->client->getChannelById(self::CHANNEL_UUID);

        $this->assertSame(self::CHANNEL_UUID, $c->getId());
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetChannelByIdThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);
        $this->expectRequest('GET', '/channels/'.self::CHANNEL_UUID, [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getChannelById(self::CHANNEL_UUID);
    }

    public function testGetChannelByIdWithEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelById('');
    }

    public function testGetChannelByNameSucceeds(): void
    {
        $responseData = ['name' => self::CHANNEL_NAME, 'id' => self::CHANNEL_UUID];

        $response = $this->buildResponse(200, $responseData);
        $this->expectRequest('GET', '/teams/'.self::TEAM_ID.'/channels/name/'.self::CHANNEL_NAME, [], $response);
        $this->expectHydration($response, Channel::class);
        $c = $this->client->getChannelByName(self::TEAM_ID, self::CHANNEL_NAME);

        $this->assertSame(self::CHANNEL_UUID, $c->getId());
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetChannelByNameThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);
        $this->expectRequest('GET', '/teams/'.self::TEAM_ID.'/channels/name/'.self::CHANNEL_NAME, [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getChannelByName(self::TEAM_ID, self::CHANNEL_NAME);
    }

    /**
     * @return iterable<array{string, string}>
     */
    public static function provideEmtpyStringParam1AndParam2(): iterable
    {
        yield ['', ''];
        yield ['team', ''];
        yield ['', 'channel'];
    }

    #[DataProvider('provideEmtpyStringParam1AndParam2')]
    public function testGetChannelByNameWithEmptyThrows(string $teamId, string $channelName): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelByName($teamId, $channelName);
    }

    public function testGetChannelByNameAndTeamNameSucceeds(): void
    {
        $responseData = ['name' => self::CHANNEL_NAME, 'id' => self::CHANNEL_UUID];

        $response = $this->buildResponse(200, $responseData);
        $this->expectRequest('GET', '/teams/name/'.self::TEAM_NAME.'/channels/name/'.self::CHANNEL_NAME, [], $response);
        $this->expectHydration($response, Channel::class);
        $c = $this->client->getChannelByNameAndTeamName(self::TEAM_NAME, self::CHANNEL_NAME);

        $this->assertSame(self::CHANNEL_UUID, $c->getId());
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetChannelByNameAndTeamNameThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);
        $this->expectRequest('GET', '/teams/name/'.self::TEAM_NAME.'/channels/name/'.self::CHANNEL_NAME, [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getChannelByNameAndTeamName(self::TEAM_NAME, self::CHANNEL_NAME);
    }

    #[DataProvider('provideEmtpyStringParam1AndParam2')]
    public function testGetChannelByNameAndTeamNameWithEmptyThrows(string $team, string $channel): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelByNameAndTeamName($team, $channel);
    }

    public function testGetChannelPostsSucceeds(): void
    {
        $responseData = ['posts' => [], 'order' => []];

        $response = $this->buildResponse(200, $responseData);
        $this->expectRequest('GET', '/channels/'.self::CHANNEL_UUID.'/posts', [], $response);
        $this->expectHydration($response, Posts::class);
        $p = $this->client->getChannelPosts(self::CHANNEL_UUID);

        $this->assertCount(0, $p);
    }

    public function testGetChannelPostsParametersSucceeds(): void
    {
        $responseData = ['posts' => [], 'order' => []];

        $response = $this->buildResponse(200, $responseData);
        $this->expectRequest(
            'GET',
            '/channels/'.self::CHANNEL_UUID.'/posts?per_page=10&page=10&before=1111&after=0000&since=9999',
            [],
            $response
        );
        $this->expectHydration($response, Posts::class);
        $p = $this->client->getChannelPosts(self::CHANNEL_UUID, [
            'per_page' => 10,
            'page' => 10,
            'before' => '1111',
            'after' => '0000',
            'since' => '9999',
        ]);

        $this->assertCount(0, $p);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetChannelPostsThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);
        $this->expectRequest('GET', '/channels/'.self::CHANNEL_UUID.'/posts', [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getChannelPosts(self::CHANNEL_UUID);
    }

    public function testGetChannelPostsWithEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelPosts('');
    }

    public function testDeleteChannelSucceeds(): void
    {
        $responseData = ['status' => 'OK'];

        $response = $this->buildResponse(200, $responseData);
        $this->expectRequest('DELETE', '/channels/'.self::CHANNEL_UUID, [], $response);
        $this->expectHydration($response, Status::class);
        $s = $this->client->deleteChannel(self::CHANNEL_UUID);
        $this->assertSame('OK', $s->getStatus());
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testDeleteChannelThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);
        $this->expectRequest('DELETE', '/channels/'.self::CHANNEL_UUID, [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->deleteChannel(self::CHANNEL_UUID);
    }

    public function testDeleteChannelEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->deleteChannel('');
    }

    public function testGetChannelStatsSucceeds(): void
    {
        $responseData = ['member_count' => 123];

        $response = $this->buildResponse(200, $responseData);
        $this->expectRequest('GET', '/channels/'.self::CHANNEL_UUID.'/stats', [], $response);
        $this->expectHydration($response, ChannelStats::class);
        $s = $this->client->getChannelStats(self::CHANNEL_UUID);

        $this->assertSame(123, $s->getMemberCount());
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetChannelStatsException(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);
        $this->expectRequest('GET', '/channels/'.self::CHANNEL_UUID.'/stats', [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getChannelStats(self::CHANNEL_UUID);
    }

    public function testGetChannelStatsEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelStats('');
    }

    public function testRestoreChannelSucceeds(): void
    {
        $responseData = ['name' => 'name', 'id' => self::CHANNEL_UUID];

        $response = $this->buildResponse(200, $responseData);
        $this->expectRequest('POST', '/channels/'.self::CHANNEL_UUID.'/restore', [], $response);
        $this->expectHydration($response, Channel::class);

        $c = $this->client->restoreChannel(self::CHANNEL_UUID);

        $this->assertSame(self::CHANNEL_UUID, $c->getId());
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testRestoreChannelException(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);
        $this->expectRequest('POST', '/channels/'.self::CHANNEL_UUID.'/restore', [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->restoreChannel(self::CHANNEL_UUID);
    }

    public function testRestoreChannelEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->restoreChannel('');
    }

    public function testRemoveChannelMemberSucceeds(): void
    {
        $responseData = ['status' => 'OK'];

        $response = $this->buildResponse(200, $responseData);
        $this->expectRequest('DELETE', '/channels/'.self::CHANNEL_UUID.'/members/'.self::USER_UUID, [], $response);
        $this->expectHydration($response, Status::class);

        $this->client->removeChannelMember(self::CHANNEL_UUID, self::USER_UUID);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testRemoveChannelMemberException(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);
        $this->expectRequest('DELETE', '/channels/'.self::CHANNEL_UUID.'/members/'.self::USER_UUID, [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->removeChannelMember(self::CHANNEL_UUID, self::USER_UUID);
    }

    #[DataProvider('provideEmtpyStringParam1AndParam2')]
    public function testRemoveChannelMemberEmptyThrows(string $param1, string $param2): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->removeChannelMember($param1, $param2);
    }

    public function testAddChannelMemberSucceeds(): void
    {
        $requestData = [
            'channel_id' => self::CHANNEL_UUID,
            'user_id' => self::USER_UUID,
        ];
        $responseData = ['channel_id' => self::CHANNEL_UUID];

        $response = $this->buildResponse(201, $responseData);
        $this->expectRequest('POST', '/channels/'.self::CHANNEL_UUID.'/members', $requestData, $response);
        $this->expectHydration($response, ChannelMember::class);

        $m = $this->client->addChannelMember(self::CHANNEL_UUID, self::USER_UUID);

        $this->assertSame(self::CHANNEL_UUID, $m->getChannelId());
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testAddChannelMemberThrows(string $exception, int $code): void
    {
        $requestData = ['channel_id' => self::CHANNEL_UUID, 'user_id' => self::USER_UUID];

        $response = $this->buildResponse($code);
        $this->expectRequest('POST', '/channels/'.self::CHANNEL_UUID.'/members', $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->addChannelMember(self::CHANNEL_UUID, self::USER_UUID);
    }

    #[DataProvider('provideEmtpyStringParam1AndParam2')]
    public function testAddChannelMemberEmpty(string $param1, string $param2): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->addChannelMember($param1, $param2);
    }

    public function testGetChannelMembersSucceeds(): void
    {
        $responseData = [];

        $response = $this->buildResponse(200, $responseData);
        $this->expectRequest(
            'GET',
            '/channels/'.self::CHANNEL_UUID.'/members?per_page=1&page=2',
            [],
            $response
        );
        $this->expectHydration($response, ChannelMembers::class);

        $m = $this->client->getChannelMembers(self::CHANNEL_UUID, [
            'per_page' => 1,
            'page' => 2,
        ]);

        $this->assertCount(0, $m);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetChannelMembersException(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);
        $this->expectRequest('GET', '/channels/'.self::CHANNEL_UUID.'/members', [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getChannelMembers(self::CHANNEL_UUID);
    }

    public function testGetChannelMembersEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelMembers('');
    }
}
