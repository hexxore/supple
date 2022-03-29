<?php

include_once realpath(__DIR__.'/../../../').'/vendor/autoload.php';

$classLoader = new \Composer\Autoload\ClassLoader();
$classLoader->addPsr4("Hexxore\Bunch\\", __DIR__.'/../src', true);
$classLoader->addPsr4("Hexxore\Bunch\\Tests\\", __DIR__, true);
$classLoader->register();
