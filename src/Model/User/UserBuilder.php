<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\User;

use Pnz\MattermostClient\Model\ModelBuilder;
use Pnz\MattermostClient\Model\ModelBuildTargetEnum;

class UserBuilder extends ModelBuilder
{
    public function setEmail(string $email): self
    {
        $this->params['email'] = $email;

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->params['username'] = $username;

        return $this;
    }

    /**
     * Set the user password.
     */
    public function setPassword(string $password): self
    {
        $this->params['password'] = $password;

        return $this;
    }

    /**
     * Set the user first name.
     */
    public function setFirstName(string $firstName): self
    {
        $this->params['first_name'] = $firstName;

        return $this;
    }

    /**
     * Set the user last name.
     */
    public function setLastName(string $lastName): self
    {
        $this->params['last_name'] = $lastName;

        return $this;
    }

    /**
     * Set the user's nickname.
     */
    public function setNickname(string $nickname): self
    {
        $this->params['nickname'] = $nickname;

        return $this;
    }

    protected function getRequiredFields(ModelBuildTargetEnum $buildType = ModelBuildTargetEnum::BUILD_FOR_CREATE): array
    {
        return match ($buildType) {
            ModelBuildTargetEnum::BUILD_FOR_CREATE => ['username', 'email', 'password'],
            ModelBuildTargetEnum::BUILD_FOR_UPDATE => ['id'],
            default => [],
        };
    }
}
