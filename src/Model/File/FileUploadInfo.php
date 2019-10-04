<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\File;

use Pnz\MattermostClient\Model\Model;

class FileUploadInfo extends Model
{
    protected function __construct(array $data)
    {
        parent::__construct([
            'client_ids' => $data['client_ids'],
            'file_infos' => FileInfos::createFromArray($data['file_infos']),
        ]);
    }

    public function getFileInfos(): FileInfos
    {
        return $this->data['file_infos'];
    }

    /**
     * @return string[]
     */
    public function getClientIds(): array
    {
        return $this->data['client_ids'];
    }

    /**
     * {@inheritdoc}
     */
    protected static function getFields(): array
    {
        return [
            'file_infos',
            'client_ids',
        ];
    }
}
