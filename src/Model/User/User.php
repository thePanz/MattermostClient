<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\User;

use Pnz\MattermostClient\Model\Model;

final class User extends Model
{
    /**
     * @return string
     */
    public function getId()
    {
        return $this->data['id'];
    }

    /**
     * @return string
     */
    public function getCreateAt()
    {
        return $this->data['create_at'];
    }

    /**
     * @return string
     */
    public function getDeleteAt()
    {
        return $this->data['delete_at'];
    }

    /**
     * @return string
     */
    public function getUpdateAt()
    {
        return $this->data['update_at'];
    }

    public function getRoles()
    {
        return $this->data['roles'];
    }

    /**
     * @return bool
     */
    public function getAllowMarketing()
    {
        return $this->data['allow_marketing'];
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->data['locale'];
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->data['username'];
    }

    /**
     * @return string
     */
    public function getAuthData()
    {
        return $this->data['auth_data'];
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->data['email'];
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->data['first_name'];
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->data['last_name'];
    }

    /**
     * @return string
     */
    public function getNickname()
    {
        return $this->data['nickname'];
    }

    /**
     * @return bool
     */
    public function getEmailVerified()
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
