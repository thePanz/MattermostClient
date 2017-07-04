<?php

namespace Pnz\MattermostClient\Model\File;

use Pnz\MattermostClient\Model\ModelCollection;

class FileInfos extends ModelCollection
{
    /**
     * @param array $data
     *
     * @return FileInfo
     */
    protected function createItem(array $data)
    {
        return FileInfo::createFromArray($data);
    }
}
