<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model\Team;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\ModelBuildTargetEnum;
use Pnz\MattermostClient\Model\Team\TeamBuilder;

/**
 * @internal
 */
#[CoversClass(TeamBuilder::class)]
final class TeamBuilderTest extends TestCase
{
    private TeamBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new TeamBuilder();
    }

    /**
     * @return iterable<string, array{ModelBuildTargetEnum, string}>
     */
    public static function provideTeamBuilderNoParamsCases(): iterable
    {
        return [
            'create' => [ModelBuildTargetEnum::BUILD_FOR_CREATE, 'Required parameters missing: type, name, display_name'],
        ];
    }

    #[DataProvider('provideTeamBuilderNoParamsCases')]
    public function testTeamBuilderNoParams(ModelBuildTargetEnum $target, string $expectedFailureMessage): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedFailureMessage);
        $this->builder->build($target);
    }

    public function testTeamBuilderPatch(): void
    {
        $expected = [];
        $this->assertSame($expected, $this->builder->build(ModelBuildTargetEnum::BUILD_FOR_PATCH));
    }

    public function testTeamBuilderUpdate(): void
    {
        $expected = [];
        $this->assertSame($expected, $this->builder->build(ModelBuildTargetEnum::BUILD_FOR_UPDATE));
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
