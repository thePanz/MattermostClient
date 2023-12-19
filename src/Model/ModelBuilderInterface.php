<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model;

interface ModelBuilderInterface
{
    /**
     * @return array<string, mixed>
     */
    public function build(ModelBuildTargetEnum $buildTarget = ModelBuildTargetEnum::BUILD_FOR_CREATE): array;
}
