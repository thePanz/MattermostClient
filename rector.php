<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector;
use Rector\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector;
use Rector\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->rules([
        BoolReturnTypeFromStrictScalarReturnsRector::class,
        InlineConstructorDefaultToPropertyRector::class,
        PropertyTypeFromStrictSetterGetterRector::class,
        TypedPropertyFromStrictConstructorRector::class,
        NumericReturnTypeFromStrictScalarReturnsRector::class,
        ReturnTypeFromStrictNativeCallRector::class
    ]);

    $rectorConfig->sets([
        SetList::PHP_81,
        PHPUnitSetList::PHPUNIT_100,
        # \Rector\Set\ValueObject\SetList::CODE_QUALITY
    ]);

    // use imports instead of FQN: https://github.com/rectorphp/rector/blob/main/docs/auto_import_names.md#auto-import-names
    $rectorConfig->importNames();
    $rectorConfig->cacheDirectory('.cache/rector');

    // Do not import Single short classes:
    $rectorConfig->importShortClasses(false);
};
