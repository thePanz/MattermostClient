<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model\Team;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\ModelBuilder;
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

    protected function setUp(): void
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
     */
    public function testTeamBuilderNoParams(string $buildType, string $expectedFailureMessage): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedFailureMessage);
        $this->builder->build($buildType);
    }

    public function testTeamBuilderPatch(): void
    {
        $expected = [];
        $this->assertSame($expected, $this->builder->build(ModelBuilder::BUILD_FOR_PATCH));
    }

    public function testTeamBuilderUpdate(): void
    {
        $expected = [];
        $this->assertSame($expected, $this->builder->build(ModelBuilder::BUILD_FOR_UPDATE));
    }

    public function testTeamBuilderMinimal(): void
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
