<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushNextDevReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualConflictsReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetNextMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateBranchAliasReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateReplaceReleaseWorker;
use Symplify\MonorepoBuilder\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    
    $parameters->set(Option::PACKAGE_DIRECTORIES, [
        // base packages
        __DIR__ . '/packages',
        
        // add contracts?
        __DIR__ . ''
    ]);
    // for "merge" command
    $parameters->set(Option::DATA_TO_APPEND, [
        ComposerJsonSection::REQUIRE => [
            'php' => '^8.0',
        ],
        ComposerJsonSection::REQUIRE_DEV => [
            'php' => '^8.0',
            'phpunit/phpunit' => '^9.5',
            'symplify/monorepo-builder' => '^10.1',
        ],
    ]);

    // add after merge
    $parameters->set(Option::DATA_TO_APPEND, [

        ComposerJsonSection::REQUIRE_DEV => [
            'phpstan/phpstan' => '^0.12',
        ],
    ]);

    $services = $containerConfigurator->services();

    
     
    $services->set(UpdateReplaceReleaseWorker::class);
    $services->set(SetCurrentMutualConflictsReleaseWorker::class);
    $services->set(SetCurrentMutualDependenciesReleaseWorker::class);
    $services->set(TagVersionReleaseWorker::class);
    $services->set(PushTagReleaseWorker::class);
    $services->set(SetNextMutualDependenciesReleaseWorker::class);
    $services->set(UpdateBranchAliasReleaseWorker::class);
    $services->set(PushNextDevReleaseWorker::class);
    
};
