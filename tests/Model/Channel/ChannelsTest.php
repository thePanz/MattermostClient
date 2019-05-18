<?php

namespace Pnz\MattermostClient\Tests\Model\Channel;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\Channel\Channels;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\Channel\Channels
 */
class ChannelsTest extends TestCase
{
    public function testChannelsCreation(): void
    {
        $data = [
            'id' => 'id',
            'team_id' => 'team_id',
            'name' => 'Name',
            'display_name' => 'DisplayName',
            'create_at' => 'CreateAt',
            'delete_at' => 'DeleteAt',
            'update_at' => 'UpdateAt',
            'extra_update_at' => 'ExtraUpdateAt',
            'last_post_at' => 'LastPostAt',
            'header' => 'Header',
            'creator_id' => 'CreatorId',
            'purpose' => 'Purpose',
            'total_msg_count' => 'TotalMsgCount',
            'type' => 'Type',
        ];

        $channels = Channels::createFromArray([$data]);
        $this->assertCount(1, $channels);
        $channel = $channels->current();

        $this->assertSame($data['id'], $channel->getId());
        $this->assertSame($data['team_id'], $channel->getTeamId());
        $this->assertSame($data['name'], $channel->getName());
        $this->assertSame($data['display_name'], $channel->getDisplayName());
        $this->assertSame($data['create_at'], $channel->getCreateAt());
        $this->assertSame($data['delete_at'], $channel->getDeleteAt());
        $this->assertSame($data['update_at'], $channel->getUpdateAt());
        $this->assertSame($data['extra_update_at'], $channel->getExtraUpdateAt());
        $this->assertSame($data['last_post_at'], $channel->getLastPostAt());
        $this->assertSame($data['header'], $channel->getHeader());
        $this->assertSame($data['creator_id'], $channel->getCreatorId());
        $this->assertSame($data['purpose'], $channel->getPurpose());
        $this->assertSame($data['total_msg_count'], $channel->getTotalMsgCount());
        $this->assertSame($data['type'], $channel->getType());
    }
}
