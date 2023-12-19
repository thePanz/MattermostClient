<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model\User;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\ModelBuildTargetEnum;
use Pnz\MattermostClient\Model\User\UserBuilder;

/**
 * @internal
 */
#[CoversClass(UserBuilder::class)]
final class UserBuilderTest extends TestCase
{
    private UserBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new UserBuilder();
    }

    /**
     * @return iterable<string, array{ModelBuildTargetEnum, string}>
     */
    public static function provideUserBuilderNoParamsCases(): iterable
    {
        yield 'create' => [ModelBuildTargetEnum::BUILD_FOR_CREATE, 'Required parameters missing: username, email, password'];
        yield 'update' => [ModelBuildTargetEnum::BUILD_FOR_UPDATE, 'Required parameters missing: id'];
    }

    #[DataProvider('provideUserBuilderNoParamsCases')]
    public function testUserBuilderNoParams(ModelBuildTargetEnum $target, string $expectedFailureMessage): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedFailureMessage);
        $this->builder->build($target);
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
        $this->assertSame($expected, $this->builder->build(ModelBuildTargetEnum::BUILD_FOR_PATCH));
    }
}
