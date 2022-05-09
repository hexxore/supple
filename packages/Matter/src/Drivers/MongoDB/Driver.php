<?php

namespace Hexxore\Matter\Drivers\MongoDB;

use Hexxore\Matter\Drivers\DriverInterface;

class Driver implements DriverInterface {
    public function connect(): bool {
        // do something.
    }
    public function disconnect() : bool {
        // do something other.
    }
    public function commit(Closure $transaction) {
        throw new Exception("Mongo does not support transactions.");
    }

    public function getMongo() {
        return $this->mongo;
    }
}