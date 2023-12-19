<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Post;

use Pnz\MattermostClient\Model\ModelCollectionOrdered;

/**
 * @extends ModelCollectionOrdered<Post>
 */
final class Posts extends ModelCollectionOrdered
{
    protected function createItem(array $data): Post
    {
        return Post::createFromArray($data);
    }

    protected static function getItemsDataName(): string
    {
        return 'posts';
    }
}
