<?php

namespace Pnz\MattermostClient\Tests\Model\Post;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\ModelBuilder;
use Pnz\MattermostClient\Model\Post\PostBuilder;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\Post\PostBuilder
 */
class PostBuilderTest extends TestCase
{
    /**
     * @var PostBuilder
     */
    private $builder;

    public function setUp()
    {
        $this->builder = new PostBuilder();
    }

    public function provideBuildTypesForFailure()
    {
        return [
            'create' => [PostBuilder::BUILD_FOR_CREATE, 'Required parameters missing: channel_id, message'],
        ];
    }

    /**
     * @dataProvider provideBuildTypesForFailure
     *
     * @param string $buildType
     * @param string $expectedFailureMessage
     */
    public function testPostBuilderNoParams($buildType, $expectedFailureMessage)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedFailureMessage);
        $this->builder->build($buildType);
    }

    public function testPostBuilderMinimal()
    {
        $this->builder->setChannelId('channel-id');
        $this->builder->setMessage('The message');

        $expected = [
            'channel_id' => 'channel-id',
            'message' => 'The message',
        ];

        $this->assertSame($expected, $this->builder->build());
    }

    public function testPostBuilderAll()
    {
        $this->builder->setChannelId('channel-id');
        $this->builder->setMessage('The message');
        $this->builder->setIsPinned(true);
        $this->builder->setFileIds(['fid-1', 'fid-2']);
        $this->builder->setRootId('root-id');

        $expected = [
            'channel_id' => 'channel-id',
            'message' => 'The message',
            'is_pinned' => true,
            'file_ids' => ['fid-1', 'fid-2'],
            'root_id' => 'root-id',
        ];

        $this->assertSame($expected, $this->builder->build());
    }

    public function testPostBuilderUpdate()
    {
        $expected = [];
        $this->assertSame($expected, $this->builder->build(ModelBuilder::BUILD_FOR_UPDATE));
    }

    public function testPostBuilderPatch()
    {
        $expected = [];
        $this->assertSame($expected, $this->builder->build(ModelBuilder::BUILD_FOR_PATCH));
    }
}
