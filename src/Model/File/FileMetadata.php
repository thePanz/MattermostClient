<?php

namespace Pnz\MattermostClient\Model\File;


use Pnz\MattermostClient\Model\Model;

class FileMetadata extends Model
{

    /**
     * {@inheritdoc}
     */
    protected static function getFields()
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