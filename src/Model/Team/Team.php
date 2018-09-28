<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Team;

use Pnz\MattermostClient\Model\Model;

final class Team extends Model
{
    const TEAM_OPEN = 'O';
    const TEAM_INVITE_ONLY = 'I';

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

    public function getName()
    {
        return $this->data['name'];
    }

    public function getDisplayName()
    {
        return $this->data['display_name'];
    }

    public function getType()
    {
        return $this->data['type'];
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
           'name',
           'display_name',
           'type',
       ];
    }
}
