<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model;

abstract class ModelCollection implements CreatableFromArray, \Countable, \Iterator
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * Current iterator key.
     *
     * @var int
     */
    protected $key = 0;

    protected function __construct()
    {
    }

    public static function createFromArray(array $data)
    {
        $collection = new static();
        foreach ($data as $itemData) {
            $collection->items[] = $collection->createItem($itemData);
        }

        return $collection;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return \count($this->items);
    }

    public function key()
    {
        return $this->key;
    }

    public function next(): void
    {
        ++$this->key;
    }

    public function valid(): bool
    {
        return $this->key < $this->count();
    }

    public function current()
    {
        return $this->items[$this->key];
    }

    public function rewind(): void
    {
        $this->key = 0;
    }

    abstract protected function createItem(array $data);
}
