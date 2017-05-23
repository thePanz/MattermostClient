<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Channel;

use Pnz\MattermostClient\Model\ModelCollection;

final class Channels extends ModelCollection
{
    /**
     * {@inheritdoc}
     */
    protected function createItem(array $data)
    {
        return Channel::createFromArray($data);
    }
}
