<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Post;

use Pnz\MattermostClient\Model\Model;

final class Post extends Model
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->data['id'];
    }

    /**
     * @return int
     */
    public function getCreateAt(): int
    {
        return $this->data['create_at'];
    }

    /**
     * @return int
     */
    public function getUpdateAt(): int
    {
        return $this->data['update_at'];
    }

    /**
     * @return int
     */
    public function getDeleteAt(): int
    {
        return $this->data['delete_at'];
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->data['message'];
    }

    /**
     * @return bool
     */
    public function getIsPinned(): bool
    {
        return $this->data['is_pinned'];
    }

    /**
     * @return array
     */
    protected static function getFields()
    {
        return [
           'id',
           'create_at',
           'update_at',
           'delete_at',
           'user_id',
           'channel_id',
           'is_pinned',
           'root_id',
           'parent_id',
           'original_id',
           'message',
           'type',
           'props',
           'hashtag',
           'filenames',
           'file_ids',
           'pending_post_id',
       ];
    }
}
