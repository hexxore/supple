<?php

include_once __DIR__.'/vendor/autoload.php';
// add all packages here dynamically.
$classLoader = new \Composer\Autoload\ClassLoader();
$classLoader->addPsr4("Hexxore\Matter\\", __DIR__.'/../src', true);
$classLoader->addPsr4("Hexxore\Matter\\Tests\\", __DIR__, true);
$classLoader->register();
