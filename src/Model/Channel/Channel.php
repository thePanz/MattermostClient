<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Channel;

use Pnz\MattermostClient\Model\Model;

final class Channel extends Model
{
    public const CHANNEL_DIRECT = 'D';
    public const CHANNEL_OPEN = 'O';
    public const CHANNEL_PRIVATE = 'P';

    public function getId(): ?string
    {
        return $this->data['id'];
    }

    public function getCreateAt(): ?int
    {
        return $this->data['create_at'];
    }

    public function getUpdateAt(): ?int
    {
        return $this->data['update_at'];
    }

    public function getDeleteAt(): ?int
    {
        return $this->data['delete_at'];
    }

    public function getTeamId(): ?string
    {
        return $this->data['team_id'];
    }

    public function getType(): ?string
    {
        return $this->data['type'];
    }

    public function getDisplayName(): ?string
    {
        return $this->data['display_name'];
    }

    public function getName(): ?string
    {
        return $this->data['name'];
    }

    public function getHeader(): ?string
    {
        return $this->data['header'];
    }

    public function getPurpose(): ?string
    {
        return $this->data['purpose'];
    }

    public function getLastPostAt(): ?int
    {
        return $this->data['last_post_at'];
    }

    public function getTotalMsgCount(): ?int
    {
        return $this->data['total_msg_count'];
    }

    public function getExtraUpdateAt(): ?int
    {
        return $this->data['extra_update_at'];
    }

    public function getCreatorId(): ?string
    {
        return $this->data['creator_id'];
    }

    protected static function getFields(): array
    {
        return [
            'id',
            'create_at',
            'update_at',
            'delete_at',
            'team_id',
            'type',
            'display_name',
            'name',
            'header',
            'purpose',
            'last_post_at',
            'total_msg_count',
            'extra_update_at',
            'creator_id',
        ];
    }
}
