<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\User;

use Pnz\MattermostClient\Model\ModelCollection;

/**
 * @extends ModelCollection<AccessToken>
 */
final class AccessTokens extends ModelCollection
{
    protected function createItem(array $data): AccessToken
    {
        return AccessToken::createFromArray($data);
    }
}
