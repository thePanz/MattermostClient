<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Team;

use Pnz\MattermostClient\Model\ModelCollection;

/**
 * @extends ModelCollection<TeamMember>
 */
final class TeamMembers extends ModelCollection
{
    protected function createItem(array $data): TeamMember
    {
        return TeamMember::createFromArray($data);
    }
}
