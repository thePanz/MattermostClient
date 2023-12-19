<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model;

/**
 * @template T
 *
 * @extends AbstractCollection<T>
 */
abstract class ModelCollectionOrdered extends AbstractCollection
{
    /**
     * @var string[]
     */
    protected array $order = [];

    final private function __construct() {}

    public static function createFromArray(array $data): static
    {
        $collection = new static();

        foreach ($data[static::getItemsDataName()] as $itemId => $itemData) {
            $collection->items[$itemId] = $collection->createItem($itemData);
        }
        $collection->order = $data['order'];

        return $collection;
    }

    public function current(): mixed
    {
        return $this->items[$this->order[$this->key]];
    }

    public function key(): string
    {
        return $this->order[$this->key];
    }

    public function valid(): bool
    {
        return $this->key < \count($this->order);
    }

    /**
     * @return string[]
     */
    public function getOrder(): array
    {
        return $this->order;
    }

    /**
     * Returns the name of the data containing the items to be sorted.
     */
    abstract protected static function getItemsDataName(): string;
}
