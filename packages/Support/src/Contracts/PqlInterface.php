<?php

namespace Hexxore\Support\Contracts;

interface PqlInterface {
    public function from(CollectionInterface $collectable ): QueryableInterface;
    public function where(Closure $expression): QueryableInterface;
    public function filter(Closure $xpression): QueryableInterface;
    public function join(array $list ): QueryableInterface;
    
}