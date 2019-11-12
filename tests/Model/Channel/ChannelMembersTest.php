<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model\Channel;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\Channel\ChannelMembers;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\Channel\ChannelMembers
 */
class ChannelMembersTest extends TestCase
{
    public function testChannelCreation(): void
    {
        $data = [
            'channel_id' => 'channel-id',
            'user_id' => 'user-id',
            'msg_count' => 10,
            'roles' => 'role1, role2',
            'last_update_at' => 1234567890,
            'last_viewed_at' => 1234567891,
            'mention_count' => 30,
            'notify_props' => [],
        ];

        $members = ChannelMembers::createFromArray([$data]);
        $this->assertCount(1, $members);
        $member = $members->current();

        $this->assertSame($data['channel_id'], $member->getChannelId());
        $this->assertSame($data['user_id'], $member->getUserId());
        $this->assertSame($data['msg_count'], $member->getMsgCount());
        $this->assertSame($data['roles'], $member->getRoles());
        $this->assertSame($data['last_update_at'], $member->getLastUpdateAt());
        $this->assertSame($data['last_viewed_at'], $member->getLastViewedAt());
        $this->assertSame($data['mention_count'], $member->getMentionCount());
        $this->assertSame($data['notify_props'], $member->getNotifyProps());
    }
}
