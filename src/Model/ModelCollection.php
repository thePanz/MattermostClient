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

    /**
     * {@inheritdoc}
     */
    public static function createFromArray(array $data)
    {
        $collection = new static();
        foreach ($data as $itemData) {
            $collection->items[] = $collection->createItem($itemData);
        }

        return $collection;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        ++$this->key;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->key < $this->count();
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->items[$this->key];
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->key = 0;
    }

    /**
     * @param array $data
     */
    abstract protected function createItem(array $data);
}
