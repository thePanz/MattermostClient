<?php

namespace Pnz\MattermostClient\Tests\Model\Channel;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Channel\ChannelBuilder;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\Channel\ChannelBuilder
 */
class ChannelBuilderTest extends TestCase
{
    /**
     * @var ChannelBuilder
     */
    private $builder;

    public function setUp()
    {
        $this->builder = new ChannelBuilder();
    }

    public function provideBuildTypesForFailure()
    {
        return [
            'create' => [ChannelBuilder::BUILD_FOR_CREATE, 'Required parameters missing: team_id, name, display_name, type'],
            'update' => [ChannelBuilder::BUILD_FOR_PATCH, 'Required parameters missing: id'],
        ];
    }

    /**
     * @dataProvider provideBuildTypesForFailure
     *
     * @param string $buildType
     * @param string $expectedFailureMessage
     */
    public function testChannelBuilderNoParams($buildType, $expectedFailureMessage)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedFailureMessage);
        $this->builder->build($buildType);
    }

    public function testChannelBuilderMinimal()
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
}
