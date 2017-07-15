<?php

namespace Pnz\MattermostClient\Tests\Model;

use Pnz\MattermostClient\Model\ModelCollection;

class WrappedModelCollection extends ModelCollection
{
    /**
     * {@inheritdoc}
     */
    protected function createItem(array $data)
    {
        return $data;
    }
}
