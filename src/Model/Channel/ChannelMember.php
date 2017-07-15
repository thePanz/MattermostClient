<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Channel;

use Pnz\MattermostClient\Model\Model;

final class ChannelMember extends Model
{
    /**
     * @return string|null
     */
    public function getChannelId()
    {
        return $this->data['channel_id'];
    }

    /**
     * @return string|null
     */
    public function getUserId()
    {
        return $this->data['user_id'];
    }

    /**
     * @return string|null
     */
    public function getRoles()
    {
        return $this->data['roles'];
    }

    /**
     * @return int|null
     */
    public function getLastViewedAt()
    {
        return $this->data['last_viewed_at'];
    }

    /**
     * @return int|null
     */
    public function getMsgCount()
    {
        return $this->data['msg_count'];
    }

    /**
     * @return int|null
     */
    public function getMentionCount()
    {
        return $this->data['mention_count'];
    }

    /**
     * @return string|null
     */
    public function getNotifyProps()
    {
        return $this->data['notify_props'];
    }

    /**
     * @return int|null
     */
    public function getLastUpdateAt()
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
