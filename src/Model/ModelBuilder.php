<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model;

use Pnz\MattermostClient\Exception\InvalidArgumentException;

abstract class ModelBuilder implements ModelBuilderInterface
{
    public const BUILD_FOR_CREATE = 'create';
    public const BUILD_FOR_UPDATE = 'update';
    public const BUILD_FOR_PATCH = 'patch';

    protected $params = [];

    public function build(string $buildType = self::BUILD_FOR_CREATE): array
    {
        $this->validate($buildType);

        return $this->params;
    }

    /**
     * Defined the required fields for the given build type.
     *
     * @param string $buildType the build type see BUILD_FOR_* consts
     */
    abstract protected function getRequiredFields(string $buildType = self::BUILD_FOR_CREATE): array;

    /**
     * Validate the parameters.
     *
     * @param string $buildType the build type to validate the parameters
     */
    protected function validate(string $buildType = self::BUILD_FOR_CREATE): void
    {
        $requiredFields = $this->getRequiredFields($buildType);

        if (empty($requiredFields)) {
            return;
        }

        $missingFields = array_diff_key(array_flip($requiredFields), $this->params);
        if (!empty($missingFields)) {
            throw new InvalidArgumentException(sprintf('Required parameters missing: %s', implode(', ', array_keys($missingFields))));
        }
    }
}
