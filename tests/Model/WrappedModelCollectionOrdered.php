<?php

namespace Pnz\MattermostClient\Tests\Model;

use Pnz\MattermostClient\Model\ModelCollectionOrdered;

class WrappedModelCollectionOrdered extends ModelCollectionOrdered
{
    /**
     * {@inheritdoc}
     */
    protected function createItem(array $data)
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    protected static function getItemsDataName()
    {
        return 'items';
    }
}
