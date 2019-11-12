<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model\Channel;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\Channel\ChannelMember;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\Channel\ChannelMember
 */
class ChannelMemberTest extends TestCase
{
    public function testChannelMemberCreationEmpty(): void
    {
        $data = [];

        $member = ChannelMember::createFromArray($data);

        $this->assertNull($member->getChannelId());
        $this->assertNull($member->getUserId());
        $this->assertNull($member->getMsgCount());
        $this->assertNull($member->getRoles());
        $this->assertNull($member->getLastUpdateAt());
        $this->assertNull($member->getLastViewedAt());
        $this->assertNull($member->getMentionCount());
        $this->assertNull($member->getNotifyProps());
    }

    public function testChannelMemberCreation(): void
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

        $member = ChannelMember::createFromArray($data);

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
