<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return function (ContainerConfigurator $configurator) {

    $services = $configurator->services()
            ->defaults()
            ->autoconfigure(true)
            ->autowire(true);

    $services->instanceof(\Symfony\Component\Console\Command\Command::class)
             ->tag('app.command');

    $services->load('Hexxore\\Omni\\', __DIR__.'/../app/*');

    $services->set(\Hexxore\Omni\Omni::class)
             ->public()
             ->args([tagged_iterator('app.command')]);
};
