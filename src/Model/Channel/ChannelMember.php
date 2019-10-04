<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Channel;

use Pnz\MattermostClient\Model\Model;

final class ChannelMember extends Model
{
    public function getChannelId(): ?string
    {
        return $this->data['channel_id'];
    }

    public function getUserId(): ?string
    {
        return $this->data['user_id'];
    }

    public function getRoles(): ?string
    {
        return $this->data['roles'];
    }

    public function getLastViewedAt(): ?int
    {
        return $this->data['last_viewed_at'];
    }

    public function getMsgCount(): ?int
    {
        return $this->data['msg_count'];
    }

    public function getMentionCount(): ?int
    {
        return $this->data['mention_count'];
    }

    public function getNotifyProps(): ?array
    {
        return $this->data['notify_props'];
    }

    public function getLastUpdateAt(): ?int
    {
        return $this->data['last_update_at'];
    }

    protected static function getFields(): array
    {
        return [
            'channel_id',
            'user_id',
            'roles',
            'last_viewed_at',
            'msg_count',
            'mention_count',
            'notify_props',
            'last_update_at',
        ];
    }
}
