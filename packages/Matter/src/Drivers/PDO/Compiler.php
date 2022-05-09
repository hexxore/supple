<?php

// PDO compiler compiles into ANSI sql.
namespace Hexxore\Matter\Drivers\PDO;

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

    }
    public function visitWhereCondition(WhereCondition $node){
        // root node
        return $node->node->accept($this);
    }
    public function visitEquals(Equals $node){
        $lft = $node->left->accept($this);
        $rgt = $node->right->accept($this);
        if ( $rgt === null ) {
            return $node->left->accept($this).' IS NULL';
        }
        return $node->left->accept($this).' = '.$node->right->accept($this);
    }
    public function visitNotEquals(Equals $node){
        return $node->left->accept($this).' != '.$node->right->accept($this);
    }
    public function visitGreaterThan(GreaterThan $node){
        return $node->left->accept($this).' > '.$node->right->accept($this);
    }
    public function visitLesserThan(LesserThan $node){
        return $node->left->accept($this).' < '.$node->right->accept($this);
    }
    public function visitLogicalAnd(LogicalAnd $node){
        $acceptednodes = array_map(fn($n) => $n->accept($this), $node->nodes);
        return '(' . implode( " AND ", $acceptednodes) . ')';
    }
    public function visitLogicalOr(LogicalOr $node){
        $acceptednodes = array_map(fn($n) => $n->accept($this), $node->nodes);
        return '(' . implode( " OR ", $acceptednodes) . ')';
    }
    public function visitKey(Key $node) {
        // snake case the name
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $node->key));
    }
    public function visitValue(Value $node) {
        $value = $node->value;
        if ( (int)$value == $value ) {
            return $value; // pure integer
        }
        if ( $value[0] == ":" ) {
            return $value; // binding parameter
        }
        // asume as string
        return "'".$value."'";
    }
    public function compile() {
        // this limits the query to a string. not verry handy, but for SQL is expected.
       return $this->query->accept($this);
    }
}