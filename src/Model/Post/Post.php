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
        return $this->data['id'] ?? '';
    }

    /**
     * @return int
     */
    public function getCreateAt(): int
    {
        return $this->data['create_at'] ?? 0;
    }

    /**
     * @return int
     */
    public function getUpdateAt(): int
    {
        return $this->data['update_at'] ?? 0;
    }

    /**
     * @return int
     */
    public function getDeleteAt(): int
    {
        return $this->data['delete_at'] ?? 0;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->data['message'] ?? '';
    }

    /**
     * @return bool
     */
    public function getIsPinned(): bool
    {
        return $this->data['is_pinned'] ?? false;
    }

    /**
     * @return string
     */
    public function getChannelId(): string
    {
        return $this->data['channel_id'] ?? '';
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->data['user_id'] ?? '';
    }

    /**
     * @return string
     */
    public function getRootId(): string
    {
        return $this->data['root_id'] ?? '';
    }

    /**
     * @return string
     */
    public function getParentId(): string
    {
        return $this->data['parent_id'] ?? '';
    }

    /**
     * @return string
     */
    public function getOriginalId(): string
    {
        return $this->data['original_id'] ?? '';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->data['type'] ?? '';
    }

    /**
     * @return array
     */
    public function getProps(): array
    {
        return $this->data['props'] ?? [];
    }

    /**
     * @return string
     */
    public function getHashtag(): string
    {
        return $this->data['hashtag'] ?? '';
    }

    /**
     * @return array
     */
    public function getFilenames(): array
    {
        return $this->data['filenames'] ?? [];
    }

    /**
     * @return array
     */
    public function getFileIds(): array
    {
        return $this->data['file_ids'] ?? [];
    }

    /**
     * @return array
     */
    public function getPendingPostId(): string
    {
        return $this->data['pending_post_id'] ?? '';
    }

    /**
     * @return array
     */
    protected static function getFields(): array
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
