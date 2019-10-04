<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model\Channel;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Channel\ChannelBuilder;
use Pnz\MattermostClient\Model\ModelBuilder;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\Channel\ChannelBuilder
 */
class ChannelBuilderTest extends TestCase
{
    /**
     * @var ChannelBuilder
     */
    private $builder;

    protected function setUp(): void
    {
        $this->builder = new ChannelBuilder();
    }

    public function provideBuildTypesForFailure(): iterable
    {
        yield 'create' => [ChannelBuilder::BUILD_FOR_CREATE, 'Required parameters missing: team_id, name, display_name, type'];
        yield 'update' => [ChannelBuilder::BUILD_FOR_PATCH, 'Required parameters missing: id'];
    }

    /**
     * @dataProvider provideBuildTypesForFailure
     */
    public function testChannelBuilderNoParams(string $buildType, string $expectedFailureMessage): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedFailureMessage);
        $this->builder->build($buildType);
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
        $this->assertSame($expected, $this->builder->build(ModelBuilder::BUILD_FOR_UPDATE));
    }
}
