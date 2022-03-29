<?php
declare(strict_types=1);

namespace Hexxore\Bunch\Tests\Resources;

use \Hexxore\Bunch\Collection;

class People extends Collection { 
    public function __construct(... $items) {
        parent::__construct(Human::class, $items);
    }
}