<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model;

interface ModelBuilderInterface
{
    public function build(): array;
}
