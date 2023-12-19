<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model\Team;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\Team\TeamMember;
use Pnz\MattermostClient\Model\Team\TeamMembers;

/**
 * @internal
 */
#[CoversClass(TeamMembers::class)]
final class TeamMembersTest extends TestCase
{
    public function testTeamMembersCreation(): void
    {
        $data = [
            'team_id' => 'team-id',
            'user_id' => 'user-id',
            'roles' => 'Roles',
            'create_at' => 1_234_567_890,
        ];

        $teamMembers = TeamMembers::createFromArray([$data]);
        $this->assertCount(1, $teamMembers);
        /** @var TeamMember $teamMember */
        $teamMember = $teamMembers->current();

        $this->assertSame($data['team_id'], $teamMember->getTeamId());
        $this->assertSame($data['user_id'], $teamMember->getUserId());
        $this->assertSame($data['create_at'], $teamMember->getCreateAt());
        $this->assertSame($data['roles'], $teamMember->getRoles());
    }
}
