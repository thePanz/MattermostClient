<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\User;

use Pnz\MattermostClient\Model\Model;

final class UserStatus extends Model
{
    public function getUserId(): string
    {
        return $this->data['user_id'];
    }

    public function getLastActivityAt(): string
    {
        return $this->data['last_activity_at'];
    }

    public function getStatus(): string
    {
        return $this->data['status'];
    }

    protected static function getFields(): array
    {
        return [
            'user_id',
            'status',
            'last_activity_at',
        ];
    }
}
