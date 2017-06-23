<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Team;

use Pnz\MattermostClient\Model\ModelBuilder;

class TeamBuilder extends ModelBuilder
{
    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->params['name'] = $name;

        return $this;
    }

    /**
     * Set the Team type.
     *
     * @param $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->params['type'] = $type;

        return $this;
    }

    /**
     * Set the display name.
     *
     * @param $diplayName
     *
     * @return $this
     */
    public function setDisplayName($diplayName)
    {
        $this->params['display_name'] = $diplayName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRequiredFields($buildType = self::BUILD_FOR_CREATE)
    {
        switch ($buildType) {
            case self::BUILD_FOR_CREATE:
                return ['type', 'name', 'display_name'];
            default:
                return [];
        }
    }
}
