<?php

namespace Pnz\MattermostClient\Model;

/**
 * @template T
 *
 * @implements \Iterator<int|string, T>
 */
abstract class AbstractCollection implements CreatableFromArray, \Countable, \Iterator
{
    /**
     * @var array<T>
     */
    protected array $items = [];
    protected int $key = 0;

    /**
     * @return array<T>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return \count($this->items);
    }

    public function key(): int|string
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

    /**
     * @return T
     */
    public function current(): mixed
    {
        return $this->items[$this->key];
    }

    public function rewind(): void
    {
        $this->key = 0;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return T
     */
    abstract protected function createItem(array $data);
}
