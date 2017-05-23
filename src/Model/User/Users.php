<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\User;

use Pnz\MattermostClient\Model\ModelCollection;

final class Users extends ModelCollection
{
    /**
     * {@inheritdoc}
     */
    protected function createItem(array $data)
    {
        return User::createFromArray($data);
    }
}
