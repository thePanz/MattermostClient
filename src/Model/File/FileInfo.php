<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\File;

use Pnz\MattermostClient\Model\Model;

class FileInfo extends Model
{
    /**
     * The unique identifier for this file.
     */
    public function getId(): string
    {
        return $this->data['id'];
    }

    /**
     * The ID of the user that uploaded this file.
     */
    public function getUserIdd(): string
    {
        return $this->data['user_id'];
    }

    /**
     * If this file is attached to a post, the ID of that post.
     */
    public function getPostId(): string
    {
        return $this->data['post_id'];
    }

    public function getCreateAt(): int
    {
        return $this->data['create_at'];
    }

    public function getUpdateAt(): int
    {
        return $this->data['update_at'];
    }

    public function getDeleteAt(): int
    {
        return $this->data['delete_at'];
    }

    /**
     * The name of the file.
     */
    public function getName(): string
    {
        return $this->data['name'];
    }

    /**
     * The extension at the end of the file name.
     */
    public function getExtension(): string
    {
        return $this->data['extension'];
    }

    /**
     * The size of the file in bytes.
     */
    public function getSize(): int
    {
        return $this->data['size'];
    }

    /**
     * The MIME type of the file.
     */
    public function getMimeType(): string
    {
        return $this->data['mime_type'];
    }

    /**
     * If this file is an image, the width of the file.
     */
    public function getWidth(): int
    {
        return $this->data['width'];
    }

    /**
     * If this file is an image, the height of the file.
     */
    public function getHeight(): int
    {
        return $this->data['height'];
    }

    /**
     * If this file is an image, whether or not it has a preview-sized version.
     */
    public function getHasPreviewImage(): bool
    {
        return $this->data['has_preview_image'];
    }

    /**
     * {@inheritdoc}
     */
    protected static function getFields(): array
    {
        return [
            'id',
            'user_id',
            'post_id',
            'create_at',
            'update_at',
            'delete_at',
            'name',
            'extension',
            'size',
            'mime_type',
            'width',
            'height',
            'has_preview_image',
        ];
    }
}
