<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Team;

use Pnz\MattermostClient\Model\Model;

final class TeamStats extends Model
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
    public function getTotalMemberCount()
    {
        return $this->data['total_member_count'];
    }

    /**
     * @return string
     */
    public function getActiveMemberCount()
    {
        return $this->data['active_member_count'];
    }

    /**
     * @return array
     */
    protected static function getFields()
    {
        return [
           'team_id',
           'total_member_count',
           'active_member_count',
       ];
    }
}
