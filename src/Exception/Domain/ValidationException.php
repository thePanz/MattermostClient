<?php

namespace Pnz\MattermostClient\Exception\Domain;

use Pnz\MattermostClient\Exception\ApiException;
use Pnz\MattermostClient\Exception\DomainException;

final class ValidationException extends ApiException implements DomainException
{
}
