<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector;
use Rector\CodingStyle\Rector\ClassLike\NewlineBetweenClassLikeStmtsRector;
use Rector\CodingStyle\Rector\ClassMethod\NewlineBeforeNewAssignSetRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Config\RectorConfig;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withCache('.cache/rector')
    ->withImportNames(importShortClasses: false)
    ->withParallel()
    ->withPhpSets()

    ->withSkip([
        AddOverrideAttributeToOverriddenMethodsRector::class,
        CatchExceptionNameMatchingTypeRector::class,
        NewlineAfterStatementRector::class,
        NewlineBeforeNewAssignSetRector::class,
        NewlineBetweenClassLikeStmtsRector::class,
    ])

    ->withCodeQualityLevel(20)
    ->withPreparedSets(deadCode: true, codingStyle: true, earlyReturn: true)
;
