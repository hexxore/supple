<?php
namespace Hexxore\Matter\Query;
class Key extends qNode implements qNodeInterface {
    public function __construct(
        public string $key
    ){}
}