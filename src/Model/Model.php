<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model;

abstract class Model implements CreatableFromArray
{
    /**
     * @var array
     */
    protected $data;

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public static function createFromArray(array $data)
    {
        // Clearing the data
        $emptyModel = array_fill_keys(static::getFields(), null);

        $data = array_merge($emptyModel, array_intersect_key($data, $emptyModel));

        return new static($data);
    }

    /**
     * @return array
     */
    abstract protected static function getFields();
}
