<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model\User;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\ModelBuilder;
use Pnz\MattermostClient\Model\User\UserBuilder;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\User\UserBuilder
 */
class UserBuilderTest extends TestCase
{
    /**
     * @var UserBuilder
     */
    private $builder;

    protected function setUp(): void
    {
        $this->builder = new UserBuilder();
    }

    public function provideBuildTypesForFailure(): iterable
    {
        yield 'create' => [UserBuilder::BUILD_FOR_CREATE, 'Required parameters missing: username, email, password'];
        yield 'update' => [UserBuilder::BUILD_FOR_UPDATE, 'Required parameters missing: id'];
    }

    /**
     * @dataProvider provideBuildTypesForFailure
     */
    public function testUserBuilderNoParams(string $buildType, string $expectedFailureMessage): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedFailureMessage);
        $this->builder->build($buildType);
    }

    public function testUserBuilderMinimal(): void
    {
        $this->builder->setUsername('username');
        $this->builder->setPassword('password');
        $this->builder->setEmail('email');

        $expected = [
            'username' => 'username',
            'password' => 'password',
            'email' => 'email',
        ];

        $this->assertSame($expected, $this->builder->build());
    }

    public function testUserBuilderFull(): void
    {
        $this->builder->setUsername('username');
        $this->builder->setPassword('password');
        $this->builder->setEmail('email');
        $this->builder->setLastName('last-name');
        $this->builder->setFirstName('first-name');
        $this->builder->setNickname('nickname');

        $expected = [
            'username' => 'username',
            'password' => 'password',
            'email' => 'email',
            'last_name' => 'last-name',
            'first_name' => 'first-name',
            'nickname' => 'nickname',
        ];

        $this->assertSame($expected, $this->builder->build());
    }

    public function testChannelBuilderPatch(): void
    {
        $expected = [];
        $this->assertSame($expected, $this->builder->build(ModelBuilder::BUILD_FOR_PATCH));
    }
}
