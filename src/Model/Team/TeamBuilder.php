<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Team;

use Pnz\MattermostClient\Model\ModelBuilder;

class TeamBuilder extends ModelBuilder
{
    /**
     * Set the Team name.
     */
    public function setName(string $name): self
    {
        $this->params['name'] = $name;

        return $this;
    }

    /**
     * Set the Team type.
     */
    public function setType(string $type): self
    {
        $this->params['type'] = $type;

        return $this;
    }

    /**
     * Set the display name.
     */
    public function setDisplayName(string $diplayName): self
    {
        $this->params['display_name'] = $diplayName;

        return $this;
    }

    protected function getRequiredFields(string $buildType = self::BUILD_FOR_CREATE): array
    {
        switch ($buildType) {
            case self::BUILD_FOR_CREATE:
                return ['type', 'name', 'display_name'];
            default:
                return [];
        }
    }
}
