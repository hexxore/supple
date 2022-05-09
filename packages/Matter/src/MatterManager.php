<?php
namespace Hexxore\Matter;

use Hexxore\Matter\Drivers\DriverInterface;

class MatterManager {
    
    public static function connect($driver, $params): DriverInterface  {
        // is it a user specified driver?
        if ( class_exists($driver) ) {
            return new $driver($params);
        }

        // matter driver?
        $matterdriver = '\\Hexxore\\Matter\\Drivers\\'.$driver.'\\Driver';
        if ( class_exists($matterdriver ) ) {
            return new $matterdriver(...$params);
        }
        throw new Exception( $driver . ' driver could not be resolved ( '.$matterdriver.' does not exist )');
        
    }
    
}