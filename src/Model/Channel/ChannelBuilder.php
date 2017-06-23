<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Channel;

use Pnz\MattermostClient\Model\ModelBuilder;

class ChannelBuilder extends ModelBuilder
{
    /**
     * @param string $teamId
     *
     * @return $this
     */
    public function setTeamId($teamId)
    {
        $this->params['team_id'] = $teamId;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->params['name'] = $name;

        return $this;
    }

    /**
     * @param string $displayName
     *
     * @return $this
     */
    public function setDisplayName($displayName)
    {
        $this->params['display_name'] = $displayName;

        return $this;
    }

    /**
     * @param string $purpose
     *
     * @return $this
     */
    public function setPurpose($purpose)
    {
        $this->params['purpose'] = $purpose;

        return $this;
    }

    /**
     * @param string $header
     *
     * @return $this
     */
    public function setHeader($header)
    {
        $this->params['header'] = $header;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->params['type'] = $type;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRequiredFields($buildType = self::BUILD_FOR_CREATE)
    {
        switch ($buildType) {
            case self::BUILD_FOR_CREATE:
                return [
                    'team_id',
                    'name',
                    'display_name',
                    'type',
                ];

            case self::BUILD_FOR_PATCH:
                return ['id'];
            case self::BUILD_FOR_UPDATE:
            default:
                return [];
        }
    }
}
