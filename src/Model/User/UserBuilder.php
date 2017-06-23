<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\User;

use Pnz\MattermostClient\Model\ModelBuilder;

class UserBuilder extends ModelBuilder
{
    /**
     * @param $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->params['email'] = $email;

        return $this;
    }

    /**
     * @param $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->params['username'] = $username;

        return $this;
    }

    /**
     * Set the user password.
     *
     * @param $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->params['password'] = $password;

        return $this;
    }

    /**
     * Set the user first name.
     *
     * @param $firstName
     *
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->params['first_name'] = $firstName;

        return $this;
    }

    /**
     * Set the user last name.
     *
     * @param $lastName
     *
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->params['last_name'] = $lastName;

        return $this;
    }

    /**
     * Set the user's nickname.
     *
     * @param $nickname
     *
     * @return $this
     */
    public function setNickname($nickname)
    {
        $this->params['nickname'] = $nickname;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRequiredFields($buildType = self::BUILD_FOR_CREATE)
    {
        switch ($buildType) {
            case self::BUILD_FOR_CREATE:
                return ['username', 'email', 'password'];
            case self::BUILD_FOR_UPDATE:
                return ['id'];
            case self::BUILD_FOR_PATCH:
            default:
                return [];
        }
    }
}
