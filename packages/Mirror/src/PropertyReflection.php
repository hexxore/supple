<?php


namespace Hexxore\Mirror;

class PropertyReflection extends \ReflectionProperty   {
    private $target = null;
    public function __construct( string $class, string $property ) {
        parent::__construct($class, $property);
    }
}