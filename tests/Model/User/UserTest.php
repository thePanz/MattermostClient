<?php

namespace Pnz\MattermostClient\Tests\Model\User;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\User\User;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\User\User
 */
class UserTest extends TestCase
{
    public function testUserCreationEmpty(): void
    {
        $data = [];
        $user = User::createFromArray($data);

        $this->assertNull($user->getId());
        $this->assertNull($user->getEmail());
        $this->assertNull($user->getUsername());
        $this->assertNull($user->getLastName());
        $this->assertNull($user->getFirstName());
        $this->assertNull($user->getLocale());
        $this->assertNull($user->getRoles());
        $this->assertNull($user->getAllowMarketing());
        $this->assertNull($user->getAuthData());
        $this->assertNull($user->getCreateAt());
        $this->assertNull($user->getEmailVerified());
        $this->assertNull($user->getNickname());
        $this->assertNull($user->getUpdateAt());
        $this->assertNull($user->getDeleteAt());
    }

    public function testUserCreation(): void
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
            'delete_at' => 'Data for: delete_at',
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
        $this->assertSame($data['delete_at'], $user->getDeleteAt());
    }
}
