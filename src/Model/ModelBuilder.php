<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model;

use Pnz\MattermostClient\Exception\InvalidArgumentException;

abstract class ModelBuilder implements ModelBuilderInterface
{
    /** @var array<string, mixed> */
    protected array $params = [];

    /**
     * @return array<string, mixed>
     */
    public function build(ModelBuildTargetEnum $buildTarget = ModelBuildTargetEnum::BUILD_FOR_CREATE): array
    {
        $this->validate($buildTarget);

        return $this->params;
    }

    /**
     * Defined the required fields for the given build type.
     *
     * @return list<string>
     */
    abstract protected function getRequiredFields(ModelBuildTargetEnum $target = ModelBuildTargetEnum::BUILD_FOR_CREATE): array;

    /**
     * Validate the parameters.
     */
    protected function validate(ModelBuildTargetEnum $buildTarget = ModelBuildTargetEnum::BUILD_FOR_CREATE): void
    {
        $requiredFields = $this->getRequiredFields($buildTarget);

        if (empty($requiredFields)) {
            return;
        }

        $missingFields = array_diff_key(array_flip($requiredFields), $this->params);
        if (!empty($missingFields)) {
            throw new InvalidArgumentException(sprintf('Required parameters missing: %s', implode(', ', array_keys($missingFields))));
        }
    }
}
