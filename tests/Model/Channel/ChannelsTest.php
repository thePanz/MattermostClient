<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model\Channel;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\Channel\Channel;
use Pnz\MattermostClient\Model\Channel\Channels;

/**
 * @internal
 */
#[CoversClass(Channels::class)]
final class ChannelsTest extends TestCase
{
    public function testChannelsCreation(): void
    {
        $data = [
            'id' => 'id',
            'team_id' => 'team_id',
            'name' => 'Name',
            'display_name' => 'DisplayName',
            'create_at' => 123_456_890,
            'delete_at' => 123_456_891,
            'update_at' => 123_456_892,
            'extra_update_at' => 1_234_567_893,
            'last_post_at' => 1_234_567_894,
            'header' => 'Header',
            'creator_id' => 'CreatorId',
            'purpose' => 'Purpose',
            'total_msg_count' => 11,
            'type' => 'Type',
        ];

        $channels = Channels::createFromArray([$data]);
        $this->assertCount(1, $channels);

        /** @var Channel $channel */
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
