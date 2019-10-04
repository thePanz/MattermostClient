<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model\Post;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\Post\Post;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\Post\Post
 */
class PostTest extends TestCase
{
    public function testPostCreationEmpty(): void
    {
        $data = [];

        $post = Post::createFromArray($data);

        $this->assertSame('', $post->getId());
        $this->assertSame(0, $post->getCreateAt());
        $this->assertSame(0, $post->getDeleteAt());
        $this->assertSame(0, $post->getUpdateAt());
        $this->assertSame('', $post->getType());
        $this->assertSame('', $post->getMessage());
        $this->assertSame('', $post->getUserId());
        $this->assertSame('', $post->getChannelId());
        $this->assertSame([], $post->getFileIds());
        $this->assertSame([], $post->getFilenames());
        $this->assertSame('', $post->getHashtag());
        $this->assertFalse($post->getIsPinned());
        $this->assertSame('', $post->getOriginalId());
        $this->assertSame('', $post->getParentId());
        $this->assertSame('', $post->getPendingPostId());
        $this->assertSame([], $post->getProps());
        $this->assertSame('', $post->getRootId());
    }

    public function testPostCreation(): void
    {
        $data = [
            'id' => 'Data for: id',
            'create_at' => 12345,
            'delete_at' => 12345,
            'update_at' => 12345,
            'type' => 'Data for: type',
            'message' => 'Data for: message',
            'user_id' => 'Data for: user_id',
            'channel_id' => 'Data for: channel_id',
            'file_ids' => [],
            'filenames' => [],
            'hashtag' => 'Data for: hashtag',
            'is_pinned' => true,
            'original_id' => 'Data for: original_id',
            'parent_id' => 'Data for: parent_id',
            'pending_post_id' => 'Data for: pending_post_id',
            'props' => [],
            'root_id' => 'Data for: root_id',
        ];

        $post = Post::createFromArray($data);

        $this->assertSame($data['id'], $post->getId());
        $this->assertSame($data['create_at'], $post->getCreateAt());
        $this->assertSame($data['delete_at'], $post->getDeleteAt());
        $this->assertSame($data['update_at'], $post->getUpdateAt());
        $this->assertSame($data['type'], $post->getType());
        $this->assertSame($data['message'], $post->getMessage());
        $this->assertSame($data['user_id'], $post->getUserId());
        $this->assertSame($data['channel_id'], $post->getChannelId());
        $this->assertSame($data['file_ids'], $post->getFileIds());
        $this->assertSame($data['filenames'], $post->getFilenames());
        $this->assertSame($data['hashtag'], $post->getHashtag());
        $this->assertTrue($post->getIsPinned());
        $this->assertSame($data['original_id'], $post->getOriginalId());
        $this->assertSame($data['parent_id'], $post->getParentId());
        $this->assertSame($data['pending_post_id'], $post->getPendingPostId());
        $this->assertSame($data['props'], $post->getProps());
        $this->assertSame($data['root_id'], $post->getRootId());
    }
}
