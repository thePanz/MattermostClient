<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model;

use Pnz\MattermostClient\Model\ModelCollection;

class WrappedModelCollection extends ModelCollection
{
    protected function createItem(array $data)
    {
        return $data;
    }
}
