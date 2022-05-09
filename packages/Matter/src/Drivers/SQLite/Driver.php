<?php

namespace Hexxore\Matter\Drivers\SQLite;

use Hexxore\Matter\Drivers\PDO\Driver as PDODriver;

/** 
 * uses the memory on sqllite database. 
 * will survive for as long as the process is running.
 * Perfect for temporary caching etc.
 */
class Driver extends PDODriver {
    public function __construct(
        $database, 
        $create = false
    ) {
        if (!$create && !file_exists($database) ){
            throw new \Exception('SQLite file "'.$database.'" does not exist.');
        }
        parent::__construct(new \Pdo('sqlite:'.$database));
    }
}