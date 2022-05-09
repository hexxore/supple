<?php
namespace Hexxore\Matter\Query;

class Value extends qNode  implements qNodeInterface {
    public function __construct(
        public mixed $value
    ){}
}