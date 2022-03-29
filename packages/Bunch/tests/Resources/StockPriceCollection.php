<?php
declare(strict_types=1);

namespace Hexxore\Bunch\Tests\Resources;

use \Hexxore\Bunch\Collection;

class StockPriceCollection extends Collection {
    public function __construct(... $items) {
        parent::__construct(null, $items);
    }

    public function getType() {
        return $this->type;
    }
}