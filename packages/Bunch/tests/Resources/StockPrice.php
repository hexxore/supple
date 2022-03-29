<?php
declare(strict_types=1);

namespace Hexxore\Bunch\Tests\Resources;

class StockPrice {
    public function __construct (
        public int $low,
        public int $high,
        public int $year,
        public int $month,
    ){

    }
}