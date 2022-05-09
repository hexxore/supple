<?php
namespace Hexxore\Matter\Query;

class WhereCondition extends qNode  implements qNodeInterface {
    public function __construct(
        public qNodeInterface $node,
    ){}
}