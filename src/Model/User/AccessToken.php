<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\User;

use Pnz\MattermostClient\Model\Model;

final class AccessToken extends Model
{
    public function getId(): ?string
    {
        return $this->data['id'];
    }

    public function getToken(): ?string
    {
        return $this->data['token'];
    }

    public function getUserId(): ?string
    {
        return $this->data['user_id'];
    }

    public function getDescription(): ?string
    {
        return $this->data['description'];
    }

    protected static function getFields(): array
    {
        return [
            'id',
            'token',
            'user_id',
            'description',
        ];
    }
}
