<?php

use Illuminate\Http\Request;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use RectorLaravel\Rector\ClassMethod\AddGenericReturnTypeToRelationsRector;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/bootstrap',
        __DIR__.'/config',
        __DIR__.'/database',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    ->withSets([
        LaravelSetList::LARAVEL_120,
    ])
    ->withRules([
        AddGenericReturnTypeToRelationsRector::class,
    ])
    ->withConfiguredRule(RenameMethodRector::class, [
        new MethodCallRename(Request::class, 'get', 'input'),
    ]);
