<?php

namespace Pnz\MattermostClient\Tests\Model\User;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\User\UserBuilder;

/**
 * @coversNothing
 */
class UserBuilderTest extends TestCase
{
    /**
     * @var UserBuilder
     */
    private $builder;

    public function setUp()
    {
        $this->builder = new UserBuilder();
    }

    public function provideBuildTypesForFailure()
    {
        return [
            'create' => [UserBuilder::BUILD_FOR_CREATE, 'Required parameters missing: username, email, password'],
            'update' => [UserBuilder::BUILD_FOR_UPDATE, 'Required parameters missing: id'],
        ];
    }

    /**
     * @dataProvider provideBuildTypesForFailure
     *
     * @param string $buildType
     * @param string $expectedFailureMessage
     */
    public function testUserBuilderNoParams($buildType, $expectedFailureMessage)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedFailureMessage);
        $this->builder->build($buildType);
    }

    public function testUserBuilderMinimal()
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
}
