<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model\Team;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\Team\Team;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\Team\Team
 */
class TeamTest extends TestCase
{
    public function testTeamCreationEmpty(): void
    {
        $data = [];

        $team = Team::createFromArray($data);

        $this->assertNull($team->getId());
        $this->assertNull($team->getType());
        $this->assertNull($team->getName());
        $this->assertNull($team->getDisplayName());
        $this->assertNull($team->getCreateAt());
        $this->assertNull($team->getUpdateAt());
    }

    public function testTeamCreation(): void
    {
        $data = [
            'id' => 'Id',
            'name' => 'Name',
            'display_name' => 'Data for: display name',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];

        $team = Team::createFromArray($data);

        $this->assertSame($data['id'], $team->getId());
        $this->assertSame($data['name'], $team->getName());
        $this->assertSame($data['display_name'], $team->getDisplayName());
        $this->assertSame($data['create_at'], $team->getCreateAt());
        $this->assertSame($data['update_at'], $team->getUpdateAt());
    }
}
