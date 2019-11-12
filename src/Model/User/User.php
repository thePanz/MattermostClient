<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\User;

use Pnz\MattermostClient\Model\Model;

final class User extends Model
{
    public function getId(): ?string
    {
        return $this->data['id'];
    }

    public function getCreateAt(): ?int
    {
        return $this->data['create_at'];
    }

    public function getDeleteAt(): ?int
    {
        return $this->data['delete_at'];
    }

    public function getUpdateAt(): ?int
    {
        return $this->data['update_at'];
    }

    public function getRoles(): ?string
    {
        return $this->data['roles'];
    }

    public function getAllowMarketing(): ?bool
    {
        return $this->data['allow_marketing'];
    }

    public function getLocale(): ?string
    {
        return $this->data['locale'];
    }

    public function getUsername(): ?string
    {
        return $this->data['username'];
    }

    public function getAuthData(): ?string
    {
        return $this->data['auth_data'];
    }

    public function getEmail(): ?string
    {
        return $this->data['email'];
    }

    public function getFirstName(): ?string
    {
        return $this->data['first_name'];
    }

    public function getLastName(): ?string
    {
        return $this->data['last_name'];
    }

    public function getNickname(): ?string
    {
        return $this->data['nickname'];
    }

    public function getEmailVerified(): ?bool
    {
        return $this->data['email_verified'];
    }

    protected static function getFields(): array
    {
        return [
            'id',
            'create_at',
            'update_at',
            'delete_at',
            'roles',
            'allow_marketing',
            'locale',
            'username',
            'auth_data',
            'email',
            'email_verified',
            'notify_props',
            'last_password_update',
            'last_name',
            'nickname',
            'first_name',
        ];
    }
}
