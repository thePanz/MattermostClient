<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\User;

use Pnz\MattermostClient\Model\Model;

final class UserStatus extends Model
{
    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->data['user_id'];
    }

    /**
     * @return string
     */
    public function getLastActivityAt()
    {
        return $this->data['last_activity_at'];
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->data['status'];
    }

    protected static function getFields(): array
    {
        return [
           'user_id',
           'status',
           'last_activity_at',
       ];
    }
}
