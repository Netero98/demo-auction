<?php

declare(strict_types=1);

use App\Rector\ConstructorPromotionExceptRector;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Entity;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\AddSeeTestAnnotationRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\PreferPHPUnitThisCallRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\YieldDataProviderRector;
use Rector\PHPUnit\CodeQuality\Rector\ClassMethod\RemoveEmptyTestMethodRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;

return RectorConfig::configure()
    ->withCache(__DIR__ . '/var/cache/.rector', FileCacheStorage::class)
    ->withPaths([
        __DIR__ . '/bin',
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/translations',
        __DIR__ . '/rector',
    ])
    ->withPhpSets(php83: true)
    ->withSets([
        DoctrineSetList::DOCTRINE_DBAL_40,
        DoctrineSetList::DOCTRINE_ORM_214,
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
        DoctrineSetList::DOCTRINE_CODE_QUALITY,

        PHPUnitSetList::PHPUNIT_100,
        PHPUnitSetList::ANNOTATIONS_TO_ATTRIBUTES,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
    ])
    ->withSkip([
        ClassPropertyAssignToConstructorPromotionRector::class,
        NullToStrictStringFuncCallArgRector::class,

        PreferPHPUnitThisCallRector::class,
        AddSeeTestAnnotationRector::class,
        YieldDataProviderRector::class,
        RemoveEmptyTestMethodRector::class,
    ])
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
    ])
    ->withConfiguredRule(ConstructorPromotionExceptRector::class, [
        ConstructorPromotionExceptRector::EXCEPT_CLASS_ATTRIBUTES => [
            Entity::class,
            Embeddable::class,
        ],
    ]);
