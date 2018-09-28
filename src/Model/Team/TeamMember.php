<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Team;

use Pnz\MattermostClient\Model\Model;

final class TeamMember extends Model
{
    /**
     * @return string
     */
    public function getTeamId()
    {
        return $this->data['team_id'];
    }

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
    public function getCreateAt()
    {
        return $this->data['create_at'];
    }

    public function getRoles()
    {
        return $this->data['roles'];
    }

    /**
     * @return array
     */
    protected static function getFields()
    {
        return [
           'team_id',
            'user_id',
            'roles',
            'create_at',
        ];
    }
}
