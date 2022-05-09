<?php


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;


$container = new ContainerBuilder();
$configlocator = new FileLocator(__DIR__.'/../bootstrap'); 

$loader = new PhpFileLoader($container, $configlocator);
$loader->load('commands.php');
$loader->load('commands.php');
$container->compile();


return $container->get(Hexxore\Omni\Omni::class);