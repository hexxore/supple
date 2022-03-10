<?php

namespace Hexxore\Habitat;
use Contracts\ApplicationInterface;

class Application implements ApplicationInterface {
    
    const UNKNOWN = "UNKNOWN";

    private $chain = [];

    public function __construct(
        private string $name = 'UNKNOWN', 
        private string $version = '0'
    )
    {
        $this->setupAutowiring();
    }
    
    public function run() {
        // do whatever is expected of us.
    }
}