<?php

namespace Pnz\MattermostClient\Model;

enum ModelBuildTargetEnum
{
    case BUILD_FOR_CREATE;
    case BUILD_FOR_UPDATE;
    case BUILD_FOR_PATCH;
}
