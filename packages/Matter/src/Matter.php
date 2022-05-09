<?php

namespace Hexxore\Matter;

use BadMethodCallException;
use \RuntimeException;

/**
 * Main matter api, pass the config, and use it.
 */
class Matter {
    
    private array $drivers = [];
    public function connect($config, $name = "default") {
        $driver = $config['driver'];
        unset($config['driver']);
        $this->drivers[$name] = MatterManager::connect($driver, $config);
    }

    // want to proxy here?
    public function __get($var) {
        return $this->drivers[$var];
    }
    // want to make this smart? check if args[0] contains a drivername ?
    // we'll see, lets not over engineer for now.
    public function __call($method, $args) {
        $driver = 'default';
        if (! method_exists($this->drivers['default'], $args ) ) {
        	throw new BadMethodCallException("Default connection driver doesn't have this argument");
		}
        return call_user_func_array([ $this->drivers[$driver], $method ], $args);
    }
    
}