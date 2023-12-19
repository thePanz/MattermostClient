<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Channel;

use Pnz\MattermostClient\Model\ModelCollection;

/**
 * @extends ModelCollection<ChannelMember>
 */
final class ChannelMembers extends ModelCollection
{
    protected function createItem(array $data)
    {
        return ChannelMember::createFromArray($data);
    }
}
