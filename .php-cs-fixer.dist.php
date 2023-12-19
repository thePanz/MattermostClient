<?php

return (new PhpCsFixer\Config())
    ->setCacheFile('.cache/php-cs-fixer.cache')
    ->setRiskyAllowed(true)
    ->setRules([
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHPUnit84Migration:risky' => true,
        'static_lambda' => true,
        'strict_comparison' => true,
        'array_syntax' => ['syntax' => 'short'],
        'strict_param' => true,
        'ternary_to_null_coalescing' => true,
        'php_unit_test_class_requires_covers' => false,
        'php_unit_test_case_static_method_calls' => ['call_type' => 'this'],
        'void_return' => true,

        // Adjust grouping of annotations
        'phpdoc_separation' => [
            'groups' => [
                ['deprecated', 'link', 'see', 'since'],
                ['author', 'copyright', 'license'],
                ['category', 'package', 'subpackage'],
                ['property', 'property-read', 'property-write'],
                ['ORM\\*'],
                ['Assert\\*'],
                ['Serializer\\*'],
                ['OA\\*'],
            ],
        ],
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('tests/fixtures')
            ->in('src')
            ->in('tests')
    )
;