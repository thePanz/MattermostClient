<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model;

use Pnz\MattermostClient\Model\ModelCollection;

/**
 * @extends ModelCollection<MockModel>
 */
final class MockModelCollection extends ModelCollection
{
    protected function createItem(array $data): MockModel
    {
        return new MockModel($data);
    }
}
