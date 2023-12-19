<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model;

abstract class Model implements CreatableFromArray
{
    /**
     * @param array<string, mixed> $data
     */
    final private function __construct(protected array $data) {}

    public static function createFromArray(array $data): static
    {
        // Clearing the data
        $emptyModel = array_fill_keys(static::getFields(), null);

        $data = [...$emptyModel, ...array_intersect_key($data, $emptyModel)];
        $data = static::prepareData($data);

        return new static($data);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    protected static function prepareData(array $data): array
    {
        return $data;
    }

    /**
     * @return string[]
     */
    abstract protected static function getFields(): array;
}
