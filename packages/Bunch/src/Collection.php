<?php declare(strict_types=1);

namespace Hexxore\Bunch;

use ArrayIterator;
use \Closure;
use \InvalidArgumentException;
use \IteratorAggregate;
use \Traversable;

class Collection implements CollectionInterface {
    protected array $items = [];
    protected ?string $type = null;
    
    public function __construct(?string $type, array $items = []) {

        if ( $type ) {
            $this->validateType($type);
            $this->type = $type;
        }
        if ( $items ) {
            $this->validateItems($items);
            $this->items = $items;
        }
    }
    
    protected function setItems(array $items = []) {
        $this->validateItems($items);
        $this->items = $items;
    }
    protected function append(mixed ...$items) {
        $this->validateItems($items);
        $this->items = [ ...$this->items, ...$items ];
    }
    protected function validateType(string &$type): void {
        switch ( $type ) {
            case 'string':
            case 'integer':
            case 'boolean':
            //case 'mixed': 
                return;
            case 'array':
                $type = 'object';  //' $type;
            default:
                if ( !class_exists($type) ) {
                    throw new InvalidArgumentException("$type is invalid type hint for collection");
                }
                return;
        }
        
    }
    protected function validateItem(mixed &$item ): void {
        // check scalar 
        switch ( gettype($item)) {
            // case 'mixed':
            case 'string':
            case 'integer':
            case 'boolean':
                return;
            case 'array':
                $item = (object)$item;
                return;
            case 'object':
                if (! $item instanceof $this->type ) {
                    throw new InvalidArgumentException(var_export($item) .' type did not match '.$this->type.' Invalid item value');
                }        
                return;
            default:
                throw new InvalidArgumentException(var_export($item) .' type did not match '.$this->type.' Invalid item value');
                return;
                
        }


        if (! $item instanceof $this->type ) {
            throw new InvalidArgumentException(var_export($item).' of type ' . gettype($item) .' Invalid item value');
        }
    }
    
    protected function detectTypeByItem(callable|bool|float|int|string|iterable|object $item ) {
        if ( !$this->type ) {
            $this->type = gettype($item);
            if ( $this->type == 'object' ) {
                $class = get_class($item);
                if ( $class != 'stdClass' && $class != 'stdObject') {
                    $this->type = $class;
                }
            }
            if ( $this->type == "array" ) {
                 $this->type = "object";
            }
        }
    }

    protected function validateItems(array &$items): void {
        if ( !$this->type ) {
            reset($items);
            $current = current($items);
            $this->detectTypeByItem($current);
        }
        foreach ($items as &$item) {
            $this->validateItem($item);
        }
    }

    protected function getItems(): array {
        return $this->items;
    }
    // tested as loop
    public function getIterator(): CollectionIterator{ // implement a custom iterator?
        return new CollectionIterator($this->getItems());
    }
    // tested count
    public function count(): int{
        return \count($this->getItems());
    }

    public function isEmpty(): bool
    {
        return empty( $this->getItems() );
    }
    public function toArray(): array {
        return $this->items;
    }

    public function first() {
        return reset($this->items);
    }
    public function last() {
        return end($this->items);
    }
    public function slice(int $offset, ?int $length = null) {
        return new self($this->type, \array_slice($this->getItems(), $offset, $length));
    }
   
    // modifier functions 
    public function reverse() {
        return new self($this->type, \array_reverse($this->toArray()));
    }
    public function merge(CollectionInterface|Array $that) {
        $context = new self($this->type);
        $context->append( ...$this->getItems() );
        $context->append( ...$that );
        return $context;
    }
    public function aggregate(Closure $expression, $seed = null) {
        return \array_reduce($this->getItems(), $expression, $seed);
    }
    // Bunching the collection
    public function select(Closure $closure) {
        return new self( null, \array_map($closure, $this->getItems()) );
    }
    public function where(Closure $closure) {
        return new self($this->type, \array_filter($this->getItems(), $closure));
    }
    public function groupBy(Closure $closure) {
        $groups = [];
        foreach ($this as $k => $v) {
            $groupname = $closure($v);
            if ( array_key_exists($groupname, $groups) ){
                $groups[$groupname]->append($v);
            } else {
                $groups[$groupname] = new self(null, [ $v ]);
            }
        }
        $groups = new self(null, $groups);
        /* foreach ( $groups as $i => $sub ) {
            $groups[$i] = new self(null, $sub);
        } */ 
        return $groups;
    }
}