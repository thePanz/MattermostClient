<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model\Channel;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\Channel\Channel;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\Channel\Channel
 */
class ChannelTest extends TestCase
{
    public function testChannelCreationEmpty(): void
    {
        $data = [];

        $channel = Channel::createFromArray($data);

        $this->assertNull($channel->getId());
        $this->assertNull($channel->getTeamId());
        $this->assertNull($channel->getName());
        $this->assertNull($channel->getDisplayName());
        $this->assertNull($channel->getCreateAt());
        $this->assertNull($channel->getDeleteAt());
        $this->assertNull($channel->getUpdateAt());
        $this->assertNull($channel->getExtraUpdateAt());
        $this->assertNull($channel->getLastPostAt());
        $this->assertNull($channel->getHeader());
        $this->assertNull($channel->getCreatorId());
        $this->assertNull($channel->getPurpose());
        $this->assertNull($channel->getTotalMsgCount());
        $this->assertNull($channel->getType());
    }

    public function testChannelCreation(): void
    {
        $data = [
            'id' => 'id',
            'team_id' => 'team_id',
            'name' => 'Name',
            'display_name' => 'DisplayName',
            'create_at' => 1234567890,
            'delete_at' => 1234567891,
            'update_at' => 1234567892,
            'extra_update_at' => 1234567893,
            'last_post_at' => 1234567894,
            'header' => 'Header',
            'creator_id' => 'CreatorId',
            'purpose' => 'Purpose',
            'total_msg_count' => 10,
            'type' => 'Type',
        ];

        $channel = Channel::createFromArray($data);

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
