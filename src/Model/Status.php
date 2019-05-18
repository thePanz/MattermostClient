<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model;

final class Status extends Model
{
    public function getStatus(): string
    {
        return $this->data['status'] ?? '';
    }

    /**
     * {@inheritdoc}
     */
    protected static function getFields(): array
    {
        return ['status'];
    }
}
