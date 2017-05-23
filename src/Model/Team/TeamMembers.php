<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Team;

use Pnz\MattermostClient\Model\ModelCollection;

final class TeamMembers extends ModelCollection
{
    /**
     * {@inheritdoc}
     */
    protected function createItem(array $data)
    {
        return TeamMember::createFromArray($data);
    }
}
