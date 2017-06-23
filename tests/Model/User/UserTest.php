<?php

namespace Pnz\MattermostClient\Tests\Model\User;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\User\User;

class UserTest extends TestCase
{
    public function testUserCreationEmpty()
    {
        $data = [];

        $user = User::createFromArray($data);

        $this->assertSame(null, $user->getId());
        $this->assertSame(null, $user->getEmail());
        $this->assertSame(null, $user->getUsername());
        $this->assertSame(null, $user->getLastName());
        $this->assertSame(null, $user->getFirstName());
        $this->assertSame(null, $user->getLocale());
        $this->assertSame(null, $user->getRoles());
        $this->assertSame(null, $user->getAllowMarketing());
        $this->assertSame(null, $user->getAuthData());
        $this->assertSame(null, $user->getCreateAt());
        $this->assertSame(null, $user->getEmailVerified());
        $this->assertSame(null, $user->getNickname());
        $this->assertSame(null, $user->getUpdateAt());
    }


    public function testUserCreation()
    {
        $data = [
            'id' => 'Data for: id',
            'username' => 'Data for: Username',
            'email' => 'Data for: email',
            'last_name' => 'Data for: last_name',
            'first_name' => 'Data for: first_name',
            'locale' => 'Data for: locale',
            'roles' => 'Data for: roles',
            'allow_marketing' => 'Data for: allow_marketing',
            'auth_data' => 'Data for: auth_data',
            'create_at' => 'Data for: create_at',
            'email_verified' => 'Data for: email_verified',
            'nickname' => 'Data for: nickname',
            'update_at' => 'Data for: update_at',
        ];

        $user = User::createFromArray($data);

        $this->assertSame($data['id'], $user->getId());
        $this->assertSame($data['email'], $user->getEmail());
        $this->assertSame($data['username'], $user->getUsername());

        $this->assertSame($data['last_name'], $user->getLastName());
        $this->assertSame($data['first_name'], $user->getFirstName());
        $this->assertSame($data['locale'], $user->getLocale());
        $this->assertSame($data['roles'], $user->getRoles());
        $this->assertSame($data['allow_marketing'], $user->getAllowMarketing());
        $this->assertSame($data['auth_data'], $user->getAuthData());
        $this->assertSame($data['create_at'], $user->getCreateAt());
        $this->assertSame($data['email_verified'], $user->getEmailVerified());
        $this->assertSame($data['nickname'], $user->getNickname());
        $this->assertSame($data['update_at'], $user->getUpdateAt());
    }

}