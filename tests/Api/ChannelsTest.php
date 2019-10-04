<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Api;

use Pnz\JsonException\Json;
use Pnz\MattermostClient\Api\ChannelsApi;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Channel\Channel;
use Pnz\MattermostClient\Model\Channel\ChannelMembers;
use Pnz\MattermostClient\Model\Channel\ChannelStats;
use Pnz\MattermostClient\Model\Post\Posts;
use Pnz\MattermostClient\Model\Status;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Api\ChannelsApi
 */
class ChannelsTest extends BaseHttpApiTest
{
    /**
     * @var ChannelsApi
     */
    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new ChannelsApi($this->httpClient, $this->requestFactory, $this->hydrator);
    }

    public function testCreateChannelSuccess(): void
    {
        $data = [
            'name' => 'name',
            'display_name' => 'display_name,',
        ];
        $this->configureMessage('POST', '/channels', [], Json::encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Channel::class);

        $this->client->createChannel($data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testCreateChannelException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $data = [
            'name' => 'name',
            'display_name' => 'display_name,',
        ];
        $this->configureMessage('POST', '/channels', [], Json::encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->createChannel($data);
    }

    public function testCreateDirectChannelSuccess(): void
    {
        $data = ['uid1', 'uid2'];
        $this->configureMessage('POST', '/channels/direct', [], Json::encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Channel::class);

        $this->client->createDirectChannel('uid1', 'uid2');
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testCreateDirectChannelException(string $exception, int $code): void
    {
        $this->expectException($exception);

        $data = ['uid1', 'uid2'];
        $this->configureMessage('POST', '/channels/direct', [], Json::encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->createDirectChannel('uid1', 'uid2');
    }

    public function testCreateDirectChannelEmptyUserId(): void
    {
        $this->expectException(InvalidArgumentException ::class);
        $this->client->createDirectChannel('uid1', '');
    }

    public function testUpdateChannelSuccess(): void
    {
        $channelId = '111';
        $data = [
            'name' => 'name,',
            'display_name' => 'display_name',
        ];

        $this->configureMessage('PUT', '/channels/'.$channelId, [], Json::encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Channel::class);
        $this->client->updateChannel($channelId, $data);
    }

    public function testUpdateChannelEmptyId(): void
    {
        $this->expectException(InvalidArgumentException ::class);
        $this->client->updateChannel('', []);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testUpdateChannelException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $channelId = '111';
        $data = [
            'display_name' => 'display_name',
            'name' => 'name,',
        ];

        $this->configureMessage('PUT', '/channels/'.$channelId, [], Json::encode($data));
        $this->configureRequestAndResponse($code);
        $this->client->updateChannel($channelId, $data);
    }

    public function testPatchChannelSuccess(): void
    {
        $channelId = '111';
        $data = [
            'username' => 'username',
            'name' => 'name,',
        ];
        $this->configureMessage('PUT', '/channels/'.$channelId.'/patch', [], Json::encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Channel::class);

        $this->client->patchChannel($channelId, $data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testPatchChannelException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $channelId = '111';
        $data = [
            'username' => 'username',
            'name' => 'name,',
        ];
        $this->configureMessage('PUT', '/channels/'.$channelId.'/patch', [], Json::encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->patchChannel($channelId, $data);
    }

    public function testPatchChannelsEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->patchChannel('', []);
    }

    public function testGetChannelByIdSuccess(): void
    {
        $channelId = '12345';
        $this->configureMessage('GET', '/channels/'.$channelId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Channel::class);
        $this->client->getChannelById($channelId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetChannelByIdException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $channelId = '12345';
        $this->configureMessage('GET', '/channels/'.$channelId);
        $this->configureRequestAndResponse($code);
        $this->client->getChannelById($channelId);
    }

    public function testGetChannelByIdEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelById('');
    }

    public function testGetChannelByNameSuccess(): void
    {
        $channelName = 'channel-name';
        $teamId = '12345';
        $this->configureMessage('GET', '/teams/'.$teamId.'/channels/name/'.$channelName);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Channel::class);
        $this->client->getChannelByName($teamId, $channelName);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetChannelByNameException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $channelName = 'channel-name';
        $teamId = '12345';
        $this->configureMessage('GET', '/teams/'.$teamId.'/channels/name/'.$channelName);
        $this->configureRequestAndResponse($code);
        $this->client->getChannelByName($teamId, $channelName);
    }

    public function testGetChannelByNameEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelByName('', '');
    }

    public function testGetChannelByNameEmptyName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelByName('12345', '');
    }

    public function testGetChannelByNameEmptyTeamId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelByName('', 'channel-name');
    }

    public function testGetChannelByNameAndTeamNameSuccess(): void
    {
        $channelName = 'channel-name';
        $teamName = 'team-name';
        $this->configureMessage('GET', '/teams/name/'.$teamName.'/channels/name/'.$channelName);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Channel::class);
        $this->client->getChannelByNameAndTeamName($teamName, $channelName);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetChannelByNameAndTeamNameException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $channelName = 'channel-name';
        $teamName = 'team-name';
        $this->configureMessage('GET', '/teams/name/'.$teamName.'/channels/name/'.$channelName);
        $this->configureRequestAndResponse($code);
        $this->client->getChannelByNameAndTeamName($teamName, $channelName);
    }

    public function testGetChannelByNameAndTeamNameEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelByNameAndTeamName('', '');
    }

    public function testGetChannelByNameAndTeamNameEmptyName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelByNameAndTeamName('12345', '');
    }

    public function testGetChannelByNameAndTeamNameEmptyTeamId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelByNameAndTeamName('', 'channel-name');
    }

    public function testGetChannelPostsSuccess(): void
    {
        $channelId = '12345';
        $this->configureMessage('GET', '/channels/'.$channelId.'/posts');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Posts::class);
        $this->client->getChannelPosts($channelId);
    }

    public function testGetChannelPostsParametersSuccess(): void
    {
        $channelId = '12345';
        $this->configureMessage(
            'GET',
            '/channels/'.$channelId.'/posts?per_page=10&page=10&before=1111&after=0000&since=9999'
        );
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Posts::class, Posts::createFromArray(['posts' => [], 'order' => []]));
        $this->client->getChannelPosts($channelId, [
            'per_page' => 10,
            'page' => 10,
            'before' => '1111',
            'after' => '0000',
            'since' => '9999',
        ]);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetChannelPostsException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $channelId = '12345';
        $this->configureMessage('GET', '/channels/'.$channelId.'/posts');
        $this->configureRequestAndResponse($code);
        $this->client->getChannelPosts($channelId);
    }

    public function testGetChannelPostsEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelPosts('');
    }

    public function testDeleteChannelSuccess(): void
    {
        $channelId = '12345';
        $this->configureMessage('DELETE', '/channels/'.$channelId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->deleteChannel($channelId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testDeleteChannelException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $channelId = '12345';
        $this->configureMessage('DELETE', '/channels/'.$channelId);
        $this->configureRequestAndResponse($code);
        $this->client->deleteChannel($channelId);
    }

    public function testDeleteChannelEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->deleteChannel('');
    }

    public function testGetChannelStatsSuccess(): void
    {
        $channelId = '12345';
        $this->configureMessage('GET', '/channels/'.$channelId.'/stats');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(ChannelStats::class);
        $this->client->getChannelStats($channelId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetChannelStatsException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $channelId = '12345';
        $this->configureMessage('GET', '/channels/'.$channelId.'/stats');
        $this->configureRequestAndResponse($code);
        $this->client->getChannelStats($channelId);
    }

    public function testGetChannelStatsEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelStats('');
    }

    public function testRestoreChannelSuccess(): void
    {
        $channelId = '12345';
        $this->configureMessage('POST', '/channels/'.$channelId.'/restore');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Channel::class);
        $this->client->restoreChannel($channelId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testRestoreChannelException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $channelId = '12345';
        $this->configureMessage('POST', '/channels/'.$channelId.'/restore');
        $this->configureRequestAndResponse($code);
        $this->client->restoreChannel($channelId);
    }

    public function testRestoreChannelEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->restoreChannel('');
    }

    public function testRemoveChannelMemberSuccess(): void
    {
        $channelId = '12345';
        $userId = '98765';
        $this->configureMessage('DELETE', '/channels/'.$channelId.'/members/'.$userId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->removeChannelMember($channelId, $userId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testRemoveChannelMemberException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $channelId = '12345';
        $userId = '98765';
        $this->configureMessage('DELETE', '/channels/'.$channelId.'/members/'.$userId);
        $this->configureRequestAndResponse($code);
        $this->client->removeChannelMember($channelId, $userId);
    }

    public function testRemoveChannelMemberEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->removeChannelMember('', '');
    }

    public function testRemoveChannelMemberEmptyChannelId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->removeChannelMember('', 'user-id');
    }

    public function testRemoveChannelMemberEmptyUserId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->removeChannelMember('channel-id', '');
    }

    public function testAddChannelMemberSuccess(): void
    {
        $channelId = '12345';
        $userId = '98765';
        $roles = 'role1, role2';

        $data = [
            'channel_id' => $channelId,
            'user_id' => $userId,
            'roles' => $roles,
        ];
        $this->configureMessage('POST', '/channels/'.$channelId.'/members', [], Json::encode($data));
        $this->configureRequestAndResponse(201);
        $this->client->addChannelMember($channelId, $userId, $roles);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testAddChannelMemberException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $channelId = '12345';
        $userId = '98765';
        $roles = 'role1, role2';

        $data = [
            'channel_id' => $channelId,
            'user_id' => $userId,
            'roles' => $roles,
        ];
        $this->configureMessage('POST', '/channels/'.$channelId.'/members', [], Json::encode($data));
        $this->configureRequestAndResponse($code);
        $this->client->addChannelMember($channelId, $userId, $roles);
    }

    public function testAddChannelMemberEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->addChannelMember('', '');
    }

    public function testAddChannelMemberEmptyChannelId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->addChannelMember('', 'user-id');
    }

    public function testAddChannelMemberEmptyUserId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->addChannelMember('channel-id', '');
    }

    public function testGetChannelMembersSuccess(): void
    {
        $channelId = '12345';
        $this->configureMessage(
            'GET',
            '/channels/'.$channelId.'/members'.
            '?per_page=1&page=2'
        );
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(ChannelMembers::class);
        $this->client->getChannelMembers($channelId, [
            'per_page' => 1,
            'page' => 2,
        ]);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetChannelMembersException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $channelId = '12345';
        $this->configureMessage('GET', '/channels/'.$channelId.'/members');
        $this->configureRequestAndResponse($code);
        $this->client->getChannelMembers($channelId);
    }

    public function testGetChannelMembersEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelMembers('');
    }
}
