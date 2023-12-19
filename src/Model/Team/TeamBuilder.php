<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Team;

use Pnz\MattermostClient\Model\ModelBuilder;
use Pnz\MattermostClient\Model\ModelBuildTargetEnum;

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

    protected function getRequiredFields(ModelBuildTargetEnum $target = ModelBuildTargetEnum::BUILD_FOR_CREATE): array
    {
        return match ($target) {
            ModelBuildTargetEnum::BUILD_FOR_CREATE => ['type', 'name', 'display_name'],
            default => [],
        };
    }
}
