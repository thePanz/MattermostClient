<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model\Team;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\Team\Teams;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\Team\Teams
 */
class TeamsTest extends TestCase
{
    public function testTeamsCreation(): void
    {
        $data = [
            'id' => 'Id',
            'name' => 'Name',
            'display_name' => 'Data for: display name',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];

        $teams = Teams::createFromArray([$data]);
        $this->assertCount(1, $teams);
        $team = $teams->current();

        $this->assertSame($data['id'], $team->getId());
        $this->assertSame($data['name'], $team->getName());
        $this->assertSame($data['display_name'], $team->getDisplayName());
        $this->assertSame($data['create_at'], $team->getCreateAt());
        $this->assertSame($data['update_at'], $team->getUpdateAt());
    }
}
