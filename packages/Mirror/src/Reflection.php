<?php
namespace Hexxore\Mirror;

use ClassReflection;


class Reflection {
    private $target = null;

    public function __construct(string $target) {
        return $this->reflectFromString($target);
    }    

    private function reflectFromString($target): void {
        /*
         *  'class\in\namespace' 
         *  class\in\namespace::class
         */
        if ( class_exists($target) ) {
            $this->target = new ClassReflection($target);
            return; // target set.
        }
        /*
         class->prop
        */
        if ( strpos($target,'->') ) {
            list($target, $prop) = explode("->",$target, 2);
            $target = new \ClassReflection($target);
            $this->target = $target->getProperty($prop);
        }
        
    }
}