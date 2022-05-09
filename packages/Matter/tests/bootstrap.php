<?php

$monorepo_bootstrap = __DIR__."/../../../bootstrap_phpunit.php";
if ( file_exists($monorepo_bootstrap ) ) {
    require_once($monorepo_bootstrap);
}