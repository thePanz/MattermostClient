<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model\Team;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\Team\TeamMember;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\Team\TeamMember
 */
class TeamMemberTest extends TestCase
{
    public function testTeamCreationEmpty(): void
    {
        $data = [];

        $teamMember = TeamMember::createFromArray($data);

        $this->assertNull($teamMember->getTeamId());
        $this->assertNull($teamMember->getUserId());
        $this->assertNull($teamMember->getRoles());
        $this->assertNull($teamMember->getCreateAt());
    }

    public function testTeamCreation(): void
    {
        $data = [
            'team_id' => 'team-id',
            'user_id' => 'user-id',
            'roles' => 'Roles',
            'create_at' => 1234567890,
        ];

        $teamMember = TeamMember::createFromArray($data);

        $this->assertSame($data['team_id'], $teamMember->getTeamId());
        $this->assertSame($data['user_id'], $teamMember->getUserId());
        $this->assertSame($data['create_at'], $teamMember->getCreateAt());
        $this->assertSame($data['roles'], $teamMember->getRoles());
    }
}
