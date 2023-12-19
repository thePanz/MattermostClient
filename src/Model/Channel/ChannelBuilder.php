<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Channel;

use Pnz\MattermostClient\Model\ModelBuilder;
use Pnz\MattermostClient\Model\ModelBuildTargetEnum;

class ChannelBuilder extends ModelBuilder
{
    public function setTeamId(string $teamId): self
    {
        $this->params['team_id'] = $teamId;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->params['name'] = $name;

        return $this;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->params['display_name'] = $displayName;

        return $this;
    }

    public function setPurpose(string $purpose): self
    {
        $this->params['purpose'] = $purpose;

        return $this;
    }

    public function setHeader(string $header): self
    {
        $this->params['header'] = $header;

        return $this;
    }

    public function setType(string $type): self
    {
        $this->params['type'] = $type;

        return $this;
    }

    protected function getRequiredFields(ModelBuildTargetEnum $buildType = ModelBuildTargetEnum::BUILD_FOR_CREATE): array
    {
        return match ($buildType) {
            ModelBuildTargetEnum::BUILD_FOR_CREATE => [
                'team_id',
                'name',
                'display_name',
                'type',
            ],
            ModelBuildTargetEnum::BUILD_FOR_PATCH => ['id'],
            default => [],
        };
    }
}
