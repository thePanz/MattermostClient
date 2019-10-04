<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model;

abstract class ModelCollectionOrdered extends ModelCollection
{
    /**
     * @var string[]
     */
    protected $order = [];

    /**
     * {@inheritdoc}
     */
    public static function createFromArray(array $data)
    {
        $collection = new static();

        foreach ($data[static::getItemsDataName()] as $itemId => $itemData) {
            $collection->items[$itemId] = $collection->createItem($itemData);
        }
        $collection->order = $data['order'];

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->items[$this->order[$this->key]];
    }

    /**
     * {@inheritdoc}
     */
    public function key()
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
