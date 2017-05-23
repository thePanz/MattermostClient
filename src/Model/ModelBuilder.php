<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model;

use Pnz\MattermostClient\Exception\InvalidArgumentException;

abstract class ModelBuilder implements ModelBuilderInterface
{
    const BUILD_FOR_CREATE = 'create';
    const BUILD_FOR_UPDATE = 'update';
    const BUILD_FOR_PATCH = 'patch';

    protected $params = [];

    public function build($buildType = self::BUILD_FOR_CREATE)
    {
        $this->validate($buildType);

        return $this->params;
    }

    /**
     * Defined the required fields for the given build type.
     *
     * @param string $buildType
     *
     * @return array
     */
    abstract protected function getRequiredFields($buildType = self::BUILD_FOR_CREATE);

    /**
     * Validate the parameters.
     *
     * @param string $buildType the build type to validate the parameters
     */
    protected function validate($buildType = self::BUILD_FOR_CREATE)
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
