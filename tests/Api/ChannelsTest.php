<?php

namespace Pnz\MattermostClient\Tests\Api;

use Pnz\MattermostClient\Api\ChannelsApi;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Channel\Channel;
use Pnz\MattermostClient\Model\Channel\ChannelMembers;
use Pnz\MattermostClient\Model\Channel\ChannelStats;
use Pnz\MattermostClient\Model\Post\Posts;
use Pnz\MattermostClient\Model\Status;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Api\Channels
 */
class ChannelsTest extends BaseHttpApiTest
{
    /**
     * @var ChannelsApi
     */
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = new ChannelsApi($this->httpClient, $this->messageFactory, $this->hydrator);
    }

    public function testCreateChannelSuccess()
    {
        $data = [
            'name' => 'name',
            'display_name' => 'display_name,',
        ];
        $this->configureMessage('POST', '/channels', [], json_encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Channel::class);

        $this->client->createChannel($data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testCreateChannelException($exception, $code)
    {
        $this->expectException($exception);
        $data = [
            'name' => 'name',
            'display_name' => 'display_name,',
        ];
        $this->configureMessage('POST', '/channels', [], json_encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->createChannel($data);
    }

    public function testUpdateChannelSuccess()
    {
        $channelId = '111';
        $data = [
            'name' => 'name,',
            'display_name' => 'display_name',
        ];

        $this->configureMessage('PUT', '/channels/'.$channelId, [], json_encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Channel::class);
        $this->client->updateChannel($channelId, $data);
    }

    public function testUpdateChannelEmptyId()
    {
        $this->expectException(InvalidArgumentException ::class);
        $this->client->updateChannel('', []);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testUpdateChannelException($exception, $code)
    {
        $this->expectException($exception);
        $channelId = '111';
        $data = [
            'display_name' => 'display_name',
            'name' => 'name,',
        ];

        $this->configureMessage('PUT', '/channels/'.$channelId, [], json_encode($data));
        $this->configureRequestAndResponse($code);
        $this->client->updateChannel($channelId, $data);
    }

    public function testPatchChannelSuccess()
    {
        $channelId = '111';
        $data = [
            'username' => 'username',
            'name' => 'name,',
        ];
        $this->configureMessage('PUT', '/channels/'.$channelId.'/patch', [], json_encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(Channel::class);

        $this->client->patchChannel($channelId, $data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testPatchChannelException($exception, $code)
    {
        $this->expectException($exception);
        $channelId = '111';
        $data = [
            'username' => 'username',
            'name' => 'name,',
        ];
        $this->configureMessage('PUT', '/channels/'.$channelId.'/patch', [], json_encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->patchChannel($channelId, $data);
    }

    public function testPatchChannelsEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->patchChannel('', []);
    }

    public function testGetChannelByIdSuccess()
    {
        $channelId = '12345';
        $this->configureMessage('GET', '/channels/'.$channelId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Channel::class);
        $this->client->getChannelById($channelId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetChannelByIdException($exception, $code)
    {
        $this->expectException($exception);
        $channelId = '12345';
        $this->configureMessage('GET', '/channels/'.$channelId);
        $this->configureRequestAndResponse($code);
        $this->client->getChannelById($channelId);
    }

    public function testGetChannelByIdEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelById('');
    }

    public function testGetChannelByNameSuccess()
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
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetChannelByNameException($exception, $code)
    {
        $this->expectException($exception);
        $channelName = 'channel-name';
        $teamId = '12345';
        $this->configureMessage('GET', '/teams/'.$teamId.'/channels/name/'.$channelName);
        $this->configureRequestAndResponse($code);
        $this->client->getChannelByName($teamId, $channelName);
    }

    public function testGetChannelByNameEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelByName('', '');
    }

    public function testGetChannelByNameEmptyName()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelByName('12345', '');
    }

    public function testGetChannelByNameEmptyTeamId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelByName('', 'channel-name');
    }

    public function testGetChannelByNameAndTeamNameSuccess()
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
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetChannelByNameAndTeamNameException($exception, $code)
    {
        $this->expectException($exception);
        $channelName = 'channel-name';
        $teamName = 'team-name';
        $this->configureMessage('GET', '/teams/name/'.$teamName.'/channels/name/'.$channelName);
        $this->configureRequestAndResponse($code);
        $this->client->getChannelByNameAndTeamName($teamName, $channelName);
    }

    public function testGetChannelByNameAndTeamNameEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelByNameAndTeamName('', '');
    }

    public function testGetChannelByNameAndTeamNameEmptyName()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelByNameAndTeamName('12345', '');
    }

    public function testGetChannelByNameAndTeamNameEmptyTeamId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelByNameAndTeamName('', 'channel-name');
    }

    public function testGetChannelPostsSuccess()
    {
        $channelId = '12345';
        $this->configureMessage('GET', '/channels/'.$channelId.'/posts');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Posts::class);
        $this->client->getChannelPosts($channelId);
    }

    public function testGetChannelPostsParametersSuccess()
    {
        $channelId = '12345';
        $this->configureMessage('GET', '/channels/'.$channelId.
            '/posts?per_page=10&page=10&before=1111&after=0000&since=9999');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Posts::class);
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
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetChannelPostsException($exception, $code)
    {
        $this->expectException($exception);
        $channelId = '12345';
        $this->configureMessage('GET', '/channels/'.$channelId.'/posts');
        $this->configureRequestAndResponse($code);
        $this->client->getChannelPosts($channelId);
    }

    public function testGetChannelPostsEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelPosts('');
    }

    public function testDeleteChannelSuccess()
    {
        $channelId = '12345';
        $this->configureMessage('DELETE', '/channels/'.$channelId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->deleteChannel($channelId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testDeleteChannelException($exception, $code)
    {
        $this->expectException($exception);
        $channelId = '12345';
        $this->configureMessage('DELETE', '/channels/'.$channelId);
        $this->configureRequestAndResponse($code);
        $this->client->deleteChannel($channelId);
    }

    public function testDeleteChannelEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->deleteChannel('');
    }

    public function testGetChannelStatsSuccess()
    {
        $channelId = '12345';
        $this->configureMessage('GET', '/channels/'.$channelId.'/stats');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(ChannelStats::class);
        $this->client->getChannelStats($channelId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetChannelStatsException($exception, $code)
    {
        $this->expectException($exception);
        $channelId = '12345';
        $this->configureMessage('GET', '/channels/'.$channelId.'/stats');
        $this->configureRequestAndResponse($code);
        $this->client->getChannelStats($channelId);
    }

    public function testGetChannelStatsEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelStats('');
    }

    public function testRestoreChannelSuccess()
    {
        $channelId = '12345';
        $this->configureMessage('POST', '/channels/'.$channelId.'/restore');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Channel::class);
        $this->client->restoreChannel($channelId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testRestoreChannelException($exception, $code)
    {
        $this->expectException($exception);
        $channelId = '12345';
        $this->configureMessage('POST', '/channels/'.$channelId.'/restore');
        $this->configureRequestAndResponse($code);
        $this->client->restoreChannel($channelId);
    }

    public function testRestoreChannelEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->restoreChannel('');
    }

    public function testRemoveChannelMemberSuccess()
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
     *
     * @param string $exception
     * @param int    $code
     */
    public function testRemoveChannelMemberException($exception, $code)
    {
        $this->expectException($exception);
        $channelId = '12345';
        $userId = '98765';
        $this->configureMessage('DELETE', '/channels/'.$channelId.'/members/'.$userId);
        $this->configureRequestAndResponse($code);
        $this->client->removeChannelMember($channelId, $userId);
    }

    public function testRemoveChannelMemberEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->removeChannelMember('', '');
    }

    public function testRemoveChannelMemberEmptyChannelId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->removeChannelMember('', 'user-id');
    }

    public function testRemoveChannelMemberEmptyUserId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->removeChannelMember('channel-id', '');
    }

    public function testAddChannelMemberSuccess()
    {
        $channelId = '12345';
        $userId = '98765';
        $roles = 'role1, role2';

        $data = [
            'channel_id' => $channelId,
            'user_id' => $userId,
            'roles' => $roles,
        ];
        $this->configureMessage('POST', '/channels/'.$channelId.'/members', [], json_encode($data));
        $this->configureRequestAndResponse(201);
        $this->client->addChannelMember($channelId, $userId, $roles);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testAddChannelMemberException($exception, $code)
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
        $this->configureMessage('POST', '/channels/'.$channelId.'/members', [], json_encode($data));
        $this->configureRequestAndResponse($code);
        $this->client->addChannelMember($channelId, $userId, $roles);
    }

    public function testAddChannelMemberEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->addChannelMember('', '');
    }

    public function testAddChannelMemberEmptyChannelId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->addChannelMember('', 'user-id');
    }

    public function testAddChannelMemberEmptyUserId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->addChannelMember('channel-id', '');
    }

    public function testGetChannelMembersSuccess()
    {
        $channelId = '12345';
        $this->configureMessage('GET', '/channels/'.$channelId.'/members'.
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
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetChannelMembersException($exception, $code)
    {
        $this->expectException($exception);
        $channelId = '12345';
        $this->configureMessage('GET', '/channels/'.$channelId.'/members');
        $this->configureRequestAndResponse($code);
        $this->client->getChannelMembers($channelId);
    }

    public function testGetChannelMembersEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getChannelMembers('');
    }
}
