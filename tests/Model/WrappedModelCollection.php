<?php

namespace Pnz\MattermostClient\Tests\Model;

use Pnz\MattermostClient\Model\ModelCollection;

class WrappedModelCollection extends ModelCollection
{
    protected function createItem(array $data)
    {
        return $data;
    }
}
