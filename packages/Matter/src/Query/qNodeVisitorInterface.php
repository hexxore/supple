<?php
// basic node visitor for compilers.
// once a new query operator/node is defined, it should be passed into here.
// that way we can ensure every compiler supports the operator/node
namespace Hexxore\Matter\Query;

interface qNodeVisitorInterface {
    public function visitWhereCondition(WhereCondition $node);
    public function visitEquals(Equals $node);
    public function visitGreaterThan(GreaterThan $node);
    public function visitLesserThan(LesserThan $node);
    public function visitLogicalAnd(LogicalAnd $node);
    public function visitLogicalOr(LogicalOr $node);
    public function visitKey(Key $node);
    public function visitValue(Value $node);
}