<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model\User;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\User\Users;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\User\Users
 */
class UsersTest extends TestCase
{
    public function testUsersCreation(): void
    {
        $data = [
            'id' => 'Data for: id',
            'username' => 'Data for: Username',
            'email' => 'Data for: email',
            'last_name' => 'Data for: last_name',
            'first_name' => 'Data for: first_name',
            'locale' => 'Data for: locale',
            'roles' => 'Data for: roles',
            'allow_marketing' => true,
            'auth_data' => 'Data for: auth_data',
            'email_verified' => false,
            'nickname' => 'Data for: nickname',
            'create_at' => 1234567890,
            'update_at' => 1234567891,
        ];

        $users = Users::createFromArray([$data]);
        $this->assertCount(1, $users);
        $user = $users->current();

        $this->assertSame($data['id'], $user->getId());
        $this->assertSame($data['email'], $user->getEmail());
        $this->assertSame($data['username'], $user->getUsername());

        $this->assertSame($data['last_name'], $user->getLastName());
        $this->assertSame($data['first_name'], $user->getFirstName());
        $this->assertSame($data['locale'], $user->getLocale());
        $this->assertSame($data['roles'], $user->getRoles());
        $this->assertTrue($user->getAllowMarketing());
        $this->assertSame($data['auth_data'], $user->getAuthData());
        $this->assertSame($data['create_at'], $user->getCreateAt());
        $this->assertFalse($user->getEmailVerified());
        $this->assertSame($data['nickname'], $user->getNickname());
        $this->assertSame($data['update_at'], $user->getUpdateAt());
    }
}
