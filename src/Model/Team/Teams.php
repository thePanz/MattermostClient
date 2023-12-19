<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Team;

use Pnz\MattermostClient\Model\ModelCollection;

/**
 * @extends ModelCollection<Team>
 */
final class Teams extends ModelCollection
{
    protected function createItem(array $data)
    {
        return Team::createFromArray($data);
    }
}
