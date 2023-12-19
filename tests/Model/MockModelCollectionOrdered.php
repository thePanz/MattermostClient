<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model;

use Pnz\MattermostClient\Model\ModelCollectionOrdered;

/**
 * @extends ModelCollectionOrdered<MockModel>
 */
final class MockModelCollectionOrdered extends ModelCollectionOrdered
{
    protected function createItem(array $data): MockModel
    {
        return new MockModel($data);
    }

    protected static function getItemsDataName(): string
    {
        return 'items';
    }
}
