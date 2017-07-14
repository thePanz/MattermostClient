<?php

namespace Pnz\MattermostClient\Tests\Model\Team;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\Team\TeamMembers;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\Team\TeamMembers
 */
class TeamMembersTest extends TestCase
{
    public function testTeamMembersCreation()
    {
        $data = [
            'team_id' => 'team-id',
            'user_id' => 'user-id',
            'roles' => 'Roles',
            'create_at' => 1234567890,
        ];

        $teamMembers = TeamMembers::createFromArray([$data]);
        $this->assertCount(1, $teamMembers);
        $teamMember = $teamMembers->current();

        $this->assertSame($data['team_id'], $teamMember->getTeamId());
        $this->assertSame($data['user_id'], $teamMember->getUserId());
        $this->assertSame($data['create_at'], $teamMember->getCreateAt());
        $this->assertSame($data['roles'], $teamMember->getRoles());
    }
}
