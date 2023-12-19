<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Channel;

use Pnz\MattermostClient\Model\ModelCollection;

/**
 * @extends ModelCollection<Channel>
 */
final class Channels extends ModelCollection
{
    protected function createItem(array $data): Channel
    {
        return Channel::createFromArray($data);
    }
}
