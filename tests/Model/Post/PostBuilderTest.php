<?php

declare(strict_types=1);

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

    protected function setUp(): void
    {
        $this->builder = new PostBuilder();
    }

    public function provideBuildTypesForFailure(): iterable
    {
        yield 'create' => [PostBuilder::BUILD_FOR_CREATE, 'Required parameters missing: channel_id, message'];
    }

    /**
     * @dataProvider provideBuildTypesForFailure
     */
    public function testPostBuilderNoParams(string $buildType, string $expectedFailureMessage): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedFailureMessage);
        $this->builder->build($buildType);
    }

    public function testPostBuilderMinimal(): void
    {
        $this->builder->setChannelId('channel-id');
        $this->builder->setMessage('The message');

        $expected = [
            'channel_id' => 'channel-id',
            'message' => 'The message',
        ];

        $this->assertSame($expected, $this->builder->build());
    }

    public function testPostBuilderAll(): void
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

    public function testPostBuilderUpdate(): void
    {
        $expected = [];
        $this->assertSame($expected, $this->builder->build(ModelBuilder::BUILD_FOR_UPDATE));
    }

    public function testPostBuilderPatch(): void
    {
        $expected = [];
        $this->assertSame($expected, $this->builder->build(ModelBuilder::BUILD_FOR_PATCH));
    }
}
