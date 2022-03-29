<?php
declare(strict_types=1);

namespace Hexxore\Bunch\Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
   public function testBase() {
       $this->assertEquals(1,1);
   }
}