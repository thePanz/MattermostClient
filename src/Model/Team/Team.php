<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Team;

use Pnz\MattermostClient\Model\Model;

final class Team extends Model
{
    public const TEAM_OPEN = 'O';
    public const TEAM_INVITE_ONLY = 'I';

    public function getId(): ?string
    {
        return $this->data['id'];
    }

    public function getCreateAt(): ?string
    {
        return $this->data['create_at'];
    }

    public function getUpdateAt(): ?string
    {
        return $this->data['update_at'];
    }

    public function getName(): ?string
    {
        return $this->data['name'];
    }

    public function getDisplayName(): ?string
    {
        return $this->data['display_name'];
    }

    public function getType(): ?string
    {
        return $this->data['type'];
    }

    protected static function getFields(): array
    {
        return [
            'id',
            'create_at',
            'update_at',
            'name',
            'display_name',
            'type',
        ];
    }
}
