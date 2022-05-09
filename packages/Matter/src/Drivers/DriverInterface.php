<?php

namespace Hexxore\Matter\Drivers;

use Closure;

interface DriverInterface {
    // basic functions
    public function connect(): bool;
    public function disconnect(): bool;
    public function commit(Closure $transaction);
    
    // works on a collection/table
    // public function find(QueryInterface $query, $iterator = null): ResultInterface;
    
    
} 