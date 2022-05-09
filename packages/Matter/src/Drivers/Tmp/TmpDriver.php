<?php

namespace Hexxore\Matter\Drivers\Tmp;
use Hexxore\Matter\Drivers\SqLite\SqLiteDriver;

/** 
 * uses the memory on sqllite database. 
 * will survive for as long as the process is running.
 * Perfect for temporary caching etc.
 */
class TmpDriver extends SqLiteDriver {
    public function __construct() {
        parent::__construct(':memory:', true);
    }
}