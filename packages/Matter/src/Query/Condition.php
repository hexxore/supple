<?php
namespace Hexxore\Matter\Query;
class Condition extends qNode  implements qNodeInterface {
    public function __construct(
        public qNodeInterface $left,
        public qNodeInterface $right,

    ){}
}