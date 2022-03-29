<?php


namespace Hexxore\Mirror;

class ClassReflection extends \ReflectionClass  {
    private $target = null;
    public function __construct( string $class ) {
        parent::__construct($class);
    }

    public function getProperties(?int $filter = null): array {
        $properties = parent::getProperties($filter);
        foreach ( $properties as $i => $p ) {
            $properties[$i] = new PropertyReflection($this->getName(), $p->getName());
        }
    }
    public function getProperty(string $name): PropertyReflection {
        return new PropertyReflection($this->getName(), $name);
    }
}