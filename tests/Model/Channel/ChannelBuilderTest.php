<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model\Channel;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Channel\ChannelBuilder;
use Pnz\MattermostClient\Model\ModelBuildTargetEnum;

/**
 * @internal
 */
#[CoversClass(ChannelBuilder::class)]
final class ChannelBuilderTest extends TestCase
{
    private ChannelBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new ChannelBuilder();
    }

    /**
     * @return iterable<string, array{ModelBuildTargetEnum, string}>
     */
    public static function provideChannelBuilderNoParamsCases(): iterable
    {
        yield 'create' => [ModelBuildTargetEnum::BUILD_FOR_CREATE, 'Required parameters missing: team_id, name, display_name, type'];
        yield 'update' => [ModelBuildTargetEnum::BUILD_FOR_PATCH, 'Required parameters missing: id'];
    }

    #[DataProvider('provideChannelBuilderNoParamsCases')]
    public function testChannelBuilderNoParams(ModelBuildTargetEnum $target, string $expectedFailureMessage): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedFailureMessage);
        $this->builder->build($target);
    }

    public function testChannelBuilderMinimal(): void
    {
        $this->builder->setTeamId('team-id');

        $this->builder->setName('name');
        $this->builder->setDisplayName('display name');
        $this->builder->setType('type');

        $expected = [
            'team_id' => 'team-id',
            'name' => 'name',
            'display_name' => 'display name',
            'type' => 'type',
        ];

        $this->assertSame($expected, $this->builder->build());
    }

    public function testChannelBuilderFull(): void
    {
        $this->builder->setTeamId('team-id');

        $this->builder->setName('name');
        $this->builder->setDisplayName('display name');
        $this->builder->setType('type');
        $this->builder->setPurpose('Purpose');
        $this->builder->setHeader('Header');

        $expected = [
            'team_id' => 'team-id',
            'name' => 'name',
            'display_name' => 'display name',
            'type' => 'type',
            'purpose' => 'Purpose',
            'header' => 'Header',
        ];

        $this->assertSame($expected, $this->builder->build());
    }

    public function testChannelBuilderUpdate(): void
    {
        $expected = [];
        $this->assertSame($expected, $this->builder->build(ModelBuildTargetEnum::BUILD_FOR_UPDATE));
    }
}
