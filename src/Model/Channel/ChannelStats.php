<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Channel;

use Pnz\MattermostClient\Model\Model;

final class ChannelStats extends Model
{
    /**
     * @return string
     */
    public function getChannelId()
    {
        return $this->data['channel_id'];
    }

    /**
     * @return string
     */
    public function getMemberCount()
    {
        return $this->data['member_count'];
    }

    /**
     * @return array
     */
    protected static function getFields()
    {
        return [
           'channel_id',
           'member_count',
       ];
    }
}
