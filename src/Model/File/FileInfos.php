<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\File;

use Pnz\MattermostClient\Model\ModelCollection;

/**
 * @extends ModelCollection<FileInfo>
 */
class FileInfos extends ModelCollection
{
    protected function createItem(array $data): FileInfo
    {
        return FileInfo::createFromArray($data);
    }
}
