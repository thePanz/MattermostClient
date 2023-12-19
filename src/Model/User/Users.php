<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\User;

use Pnz\MattermostClient\Model\ModelCollection;

/**
 * @extends ModelCollection<User>
 */
final class Users extends ModelCollection
{
    protected function createItem(array $data): User
    {
        return User::createFromArray($data);
    }
}
