<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model\Team;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\Team\TeamStats;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\Team\TeamStats
 */
class TeamStatsTest extends TestCase
{
    public function testTeamStatsCreationEmpty(): void
    {
        $data = [];

        $teamStats = TeamStats::createFromArray($data);

        $this->assertNull($teamStats->getTeamId());
        $this->assertNull($teamStats->getActiveMemberCount());
        $this->assertNull($teamStats->getTotalMemberCount());
    }

    public function testTeamStatsCreation(): void
    {
        $data = [
            'team_id' => 'team_id',
            'active_member_count' => 20,
            'total_member_count' => 10,
        ];

        $teamStats = TeamStats::createFromArray($data);

        $this->assertSame($data['team_id'], $teamStats->getTeamId());
        $this->assertSame($data['active_member_count'], $teamStats->getActiveMemberCount());
        $this->assertSame($data['total_member_count'], $teamStats->getTotalMemberCount());
    }
}
