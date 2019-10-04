<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model;

use Pnz\MattermostClient\Model\ModelCollectionOrdered;

class WrappedModelCollectionOrdered extends ModelCollectionOrdered
{
    protected function createItem(array $data)
    {
        return $data;
    }

    protected static function getItemsDataName(): string
    {
        return 'items';
    }
}
