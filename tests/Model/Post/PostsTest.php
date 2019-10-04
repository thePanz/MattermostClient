<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model\Post;

use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\Post\Post;
use Pnz\MattermostClient\Model\Post\Posts;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\Post\Posts
 */
class PostsTest extends TestCase
{
    public function testPostsCreation(): void
    {
        $post1 = $this->buildPostData('post-id-1');
        $posts = Posts::createFromArray([
            'order' => [
                'post-id-1',
            ],
            'posts' => [
                'post-id-1' => $post1,
            ],
        ]);
        $this->assertCount(1, $posts);
        $this->assertSame(['post-id-1'], $posts->getOrder());

        $this->assertSame('post-id-1', $posts->key());
        $this->assertSamePost($post1, $posts->current());
        $posts->next();
        $this->assertFalse($posts->valid());
    }

    public function testPostsCreationSorted(): void
    {
        $post1 = $this->buildPostData('post-id-1');
        $post2 = $this->buildPostData('post-id-2');
        $post3 = $this->buildPostData('post-id-3');

        $posts = Posts::createFromArray([
            'order' => [
                'post-id-2',
                'post-id-1',
                'post-id-3',
            ],
            'posts' => [
                'post-id-1' => $post1,
                'post-id-2' => $post2,
                'post-id-3' => $post3,
            ],
        ]);
        $this->assertCount(3, $posts);
        $this->assertSame([
            'post-id-2',
            'post-id-1',
            'post-id-3',
        ], $posts->getOrder());

        $this->assertSame('post-id-2', $posts->key());
        $this->assertSamePost($post2, $posts->current());
        $posts->next();
        $this->assertSame('post-id-1', $posts->key());
        $this->assertSamePost($post1, $posts->current());
        $posts->next();
        $this->assertSame('post-id-3', $posts->key());
        $this->assertSamePost($post3, $posts->current());
        $posts->next();
        $this->assertFalse($posts->valid());
    }

    private function buildPostData(string $id): array
    {
        return [
            'id' => $id,
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
    }

    private function assertSamePost(array $data, Post $post): void
    {
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
        $this->assertSame($data['is_pinned'], $post->getIsPinned());
        $this->assertSame($data['original_id'], $post->getOriginalId());
        $this->assertSame($data['parent_id'], $post->getParentId());
        $this->assertSame($data['pending_post_id'], $post->getPendingPostId());
        $this->assertSame($data['props'], $post->getProps());
        $this->assertSame($data['root_id'], $post->getRootId());
    }
}
