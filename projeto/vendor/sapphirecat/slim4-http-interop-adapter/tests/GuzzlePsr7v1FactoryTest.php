<?php

use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Factory\AppFactory;

class GuzzlePsr7v1FactoryTest extends TestCase
{
    public function testCanCreateApp(): void
    {
        $app = AppFactory::create();
        $this->assertInstanceOf(App::class, $app);
    }
}
