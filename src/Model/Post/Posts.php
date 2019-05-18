<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Post;

use Pnz\MattermostClient\Model\ModelCollectionOrdered;

final class Posts extends ModelCollectionOrdered
{
    /**
     * {@inheritdoc}
     */
    protected function createItem(array $data)
    {
        return Post::createFromArray($data);
    }

    protected static function getItemsDataName(): string
    {
        return 'posts';
    }
}
