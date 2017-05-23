<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Channel;

use Pnz\MattermostClient\Model\Model;

final class Channel extends Model
{
    const CHANNEL_OPEN = 'O';
    const CHANNEL_PRIVATE = 'P';

    /**
     * @return string
     */
    public function getId()
    {
        return $this->data['id'];
    }

    /**
     * @return string
     */
    public function getCreateAt()
    {
        return $this->data['create_at'];
    }

    /**
     * @return string
     */
    public function getUpdateAt()
    {
        return $this->data['update_at'];
    }

    /**
     * @return string
     */
    public function getDeleteAt()
    {
        return $this->data['delete_at'];
    }

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
    public function getType()
    {
        return $this->data['type'];
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->data['display_name'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->data['name'];
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->data['header'];
    }

    /**
     * @return string
     */
    public function getPurpose()
    {
        return $this->data['purpose'];
    }

    /**
     * @return string
     */
    public function getLastPostAt()
    {
        return $this->data['last_post_at'];
    }

    /**
     * @return string
     */
    public function getTotalMsgCount()
    {
        return $this->data['total_msg_count'];
    }

    /**
     * @return string
     */
    public function getExtraUpdateAt()
    {
        return $this->data['extra_update_at'];
    }

    /**
     * @return string
     */
    public function getCreatorId()
    {
        return $this->data['creator_id'];
    }

    /**
     * @return array
     */
    protected static function getFields()
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
