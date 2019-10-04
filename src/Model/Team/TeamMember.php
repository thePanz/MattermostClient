<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Team;

use Pnz\MattermostClient\Model\Model;

final class TeamMember extends Model
{
    public function getTeamId(): ?string
    {
        return $this->data['team_id'];
    }

    public function getUserId(): ?string
    {
        return $this->data['user_id'];
    }

    public function getCreateAt(): ?int
    {
        return $this->data['create_at'];
    }

    public function getRoles(): ?string
    {
        return $this->data['roles'];
    }

    protected static function getFields(): array
    {
        return [
            'team_id',
            'user_id',
            'roles',
            'create_at',
        ];
    }
}
