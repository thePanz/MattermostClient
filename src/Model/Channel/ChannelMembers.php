<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Channel;

use Pnz\MattermostClient\Model\ModelCollection;

final class ChannelMembers extends ModelCollection
{
    /**
     * {@inheritdoc}
     */
    protected function createItem(array $data)
    {
        return ChannelMember::createFromArray($data);
    }
}
