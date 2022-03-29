<?php
declare(strict_types=1);

namespace Tests\Unit;

use \Hexxore\Mirror\Reflection;

class Reflectable {
    public function __construct(
        private $var1, 
        private $var2,
    ) {

    }
}

final class ApplicationTest extends TestCase
{
    
    public function setUp() : void {
        
    }
    public function testClassReflection() {

        $cr = Reflection(Reflectable::class);
        $this->assertTrue( $cr->isClass() );

    }
    
}