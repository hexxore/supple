<?php
namespace Hexxore\Matter\Query;
class LogicalOperator extends qNode  implements qNodeInterface {
    public $nodes = [];
    public function __construct(
        ... $nodes
    ){
        $this->nodes = $nodes;
    }
}