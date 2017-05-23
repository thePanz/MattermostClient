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
     * {@inheritdoc}
     */
    protected function getRequiredFields($buildType = self::BUILD_FOR_CREATE)
    {
        return ['username', 'email', 'password'];
    }
}
