<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Channel;

use Pnz\MattermostClient\Model\Model;

final class ChannelMember extends Model
{
    /**
     * @return string
     */
    public function getChannelId(): string
    {
        return $this->data['channel_id'];
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->data['user_id'];
    }

    /**
     * @return string
     */
    public function getRoles(): string
    {
        return $this->data['roles'];
    }

    /**
     * @return int
     */
    public function getLastViewedAt(): int
    {
        return $this->data['last_viewed_at'];
    }

    /**
     * @return int
     */
    public function getMsgCount(): int
    {
        return $this->data['msg_count'];
    }

    /**
     * @return int
     */
    public function getMentionCount(): int
    {
        return $this->data['mention_count'];
    }

    /**
     * @return string
     */
    public function getNotifyProps(): string
    {
        return $this->data['notify_props'];
    }

    /**
     * @return int
     */
    public function getLastUpdateAt(): int
    {
        return $this->data['last_update_at'];
    }

    /**
     * @return array
     */
    protected static function getFields()
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
