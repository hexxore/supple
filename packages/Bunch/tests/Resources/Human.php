<?php
declare(strict_types=1);

namespace Hexxore\Bunch\Tests\Resources;

# [Collection(People)]
class Human {
    public function __construct (
        public string $name,
        public Gender $gender,
        public City $city
    ){
        
    }
}