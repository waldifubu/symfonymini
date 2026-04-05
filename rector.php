<?php

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

return RectorConfig::configure()
    ->withAttributesSets(symfony: true, doctrine: true)
    // register single rule
    ->withRules([
        TypedPropertyFromStrictConstructorRector::class
    ])
    // here we can define, what prepared sets of rules will be applied
    ->withSets([
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::PHP_84,
    ])
    ->withPhpSets(php84: true);
