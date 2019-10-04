<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Post;

use Pnz\MattermostClient\Model\Model;

final class Post extends Model
{
    public function getId(): string
    {
        return $this->data['id'] ?? '';
    }

    public function getCreateAt(): int
    {
        return $this->data['create_at'] ?? 0;
    }

    public function getUpdateAt(): int
    {
        return $this->data['update_at'] ?? 0;
    }

    public function getDeleteAt(): int
    {
        return $this->data['delete_at'] ?? 0;
    }

    public function getMessage(): string
    {
        return $this->data['message'] ?? '';
    }

    public function getIsPinned(): bool
    {
        return $this->data['is_pinned'] ?? false;
    }

    public function getChannelId(): string
    {
        return $this->data['channel_id'] ?? '';
    }

    public function getUserId(): string
    {
        return $this->data['user_id'] ?? '';
    }

    public function getRootId(): string
    {
        return $this->data['root_id'] ?? '';
    }

    public function getParentId(): string
    {
        return $this->data['parent_id'] ?? '';
    }

    public function getOriginalId(): string
    {
        return $this->data['original_id'] ?? '';
    }

    public function getType(): string
    {
        return $this->data['type'] ?? '';
    }

    public function getProps(): array
    {
        return $this->data['props'] ?? [];
    }

    public function getHashtag(): string
    {
        return $this->data['hashtag'] ?? '';
    }

    public function getFilenames(): array
    {
        return $this->data['filenames'] ?? [];
    }

    public function getFileIds(): array
    {
        return $this->data['file_ids'] ?? [];
    }

    public function getPendingPostId(): string
    {
        return $this->data['pending_post_id'] ?? '';
    }

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
