<?php

namespace Pnz\MattermostClient\Tests\Model\Team;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Team\TeamBuilder;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\Team\TeamBuilder
 */
class TeamBuilderTest extends TestCase
{
    /**
     * @var TeamBuilder
     */
    private $builder;

    public function setUp()
    {
        $this->builder = new TeamBuilder();
    }

    public function provideBuildTypesForFailure()
    {
        return [
            'create' => [TeamBuilder::BUILD_FOR_CREATE, 'Required parameters missing: type, name, display_name'],
        ];
    }

    /**
     * @dataProvider provideBuildTypesForFailure
     *
     * @param string $buildType
     * @param string $expectedFailureMessage
     */
    public function testTeamBuilderNoParams($buildType, $expectedFailureMessage)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedFailureMessage);
        $this->builder->build($buildType);
    }

    public function testTeamBuilderMinimal()
    {
        $this->builder->setType('Type');
        $this->builder->setName('Name');
        $this->builder->setDisplayName('Display Name');

        $expected = [
            'type' => 'Type',
            'name' => 'Name',
            'display_name' => 'Display Name',
        ];

        $this->assertSame($expected, $this->builder->build());
    }
}
