<?php
namespace Hexxore\Bunch;
use IteratorAggregate;
use Closure;

interface CollectionInterface extends IteratorAggregate {
    public function getIterator(): CollectionIterator; // implement a custom iterator?
    public function count(): int;
    public function isEmpty(): bool;
    public function toArray(): array;
    public function first();
    public function last();
    public function slice(int $offset, ?int $length = null);
    // modifier functions 
    public function reverse();
    public function merge(CollectionInterface|Array $that);
    public function aggregate(Closure $expression, $seed = null);
    public function select(Closure $closure);
    public function where(Closure $closure);
    public function groupBy(Closure $closure);
}
