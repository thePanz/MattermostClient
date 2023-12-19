<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model;

/**
 * @template T
 *
 * @extends AbstractCollection<T>
 */
abstract class ModelCollection extends AbstractCollection
{
    protected array $items = [];
    protected int $key = 0;

    final private function __construct() {}

    /**
     * @param list<array<mixed>> $data
     */
    public static function createFromArray(array $data): static
    {
        $collection = new static();
        foreach ($data as $itemData) {
            $collection->items[] = $collection->createItem($itemData);
        }

        return $collection;
    }
}
