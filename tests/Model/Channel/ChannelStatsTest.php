<?php

namespace Pnz\MattermostClient\Tests\Model\Channel;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\Channel\ChannelStats;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\Channel\ChannelStats
 */
class ChannelStatsTest extends TestCase
{
    public function testChannelStatsCreationEmpty()
    {
        $data = [];

        $teamStats = ChannelStats::createFromArray($data);

        $this->assertNull($teamStats->getChannelId());
        $this->assertNull($teamStats->getMemberCount());
    }

    public function testChannelStatsCreation()
    {
        $data = [
            'channel_id' => 'channel_id',
            'member_count' => 20,
        ];

        $teamStats = ChannelStats::createFromArray($data);

        $this->assertSame($data['channel_id'], $teamStats->getChannelId());
        $this->assertSame($data['member_count'], $teamStats->getMemberCount());
    }
}
