<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Team;

use Pnz\MattermostClient\Model\Model;

final class TeamStats extends Model
{
    public function getTeamId(): ?string
    {
        return $this->data['team_id'];
    }

    public function getTotalMemberCount(): ?int
    {
        return $this->data['total_member_count'];
    }

    public function getActiveMemberCount(): ?int
    {
        return $this->data['active_member_count'];
    }

    protected static function getFields(): array
    {
        return [
            'team_id',
            'total_member_count',
            'active_member_count',
        ];
    }
}
