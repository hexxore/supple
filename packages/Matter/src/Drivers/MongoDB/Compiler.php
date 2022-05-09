<?php
// Mongo compiler compiles into the mongo aggregation framework format:
// see : https://www.mongodb.com/docs/manual/aggregation/
namespace Hexxore\Matter\Drivers\MongoDB;

use Hexxore\Matter\Query\Equals;
use Hexxore\Matter\Query\GreaterThan;
use Hexxore\Matter\Query\Key;
use Hexxore\Matter\Query\LesserThan;
use Hexxore\Matter\Query\LogicalAnd;
use Hexxore\Matter\Query\LogicalOr;
use Hexxore\Matter\Query\qNodeInterface;
use Hexxore\Matter\Query\qNodeVisitorInterface;
use Hexxore\Matter\Query\Value;
use Hexxore\Matter\Query\WhereCondition;

class Compiler implements qNodeVisitorInterface {
    public function __construct(
        private qNodeInterface $query
    ){
        $this->filter = [];
    }
    public function setOrMerge($filter) {
        // add to this filter.
    }
    public function visitWhereCondition(WhereCondition $node){
        // root node
        return $node->node->accept($this);
    }
    public function visitEquals(Equals $node){
        return  [$node->left->accept($this) => $node->right->accept($this) ];
    }
    public function visitGreaterThan(GreaterThan $node){
        return  [$node->left->accept($this) => [ '$gt' => $node->right->accept($this) ]];
    }
    public function visitLesserThan(LesserThan $node){
        return  [$node->left->accept($this) => [ '$lt' => $node->right->accept($this) ]];
    }
    public function visitLogicalAnd(LogicalAnd $node){
        $current = [];
        foreach ( $node->nodes as $n ) {
            $current = array_merge_recursive($n->accept($this), $current);
        }
        return $current; //array_map(fn($n) => $n->accept($this), $node->nodes);
    }
    public function visitLogicalOr(LogicalOr $node){
        $current = [];
        foreach ( $node->nodes as $n ) {
            $current[] = $n->accept($this);
        }
       
        return [ '$or' => $current ];
    }
    public function visitKey(Key $node) {
        return $node->key;
    }
    public function visitValue(Value $node) {
        return $node->value;
    }
    public function compile() {
        // this limits the query to a string. not verry handy, but for SQL is expected.
       return $this->query->accept($this);
    }
}