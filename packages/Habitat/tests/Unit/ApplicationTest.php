<?php
declare(strict_types=1);

namespace Tests\Unit;

use \Hexxore\Habitat\Application;

final class ApplicationTest extends TestCase
{
    
    public function setUp() : void {
        
    }
    public function testNewApplication() {
        $application = new Application('test','1.0.0');

        $this->assetEqual($application::class, Application::class);
    }
    
}