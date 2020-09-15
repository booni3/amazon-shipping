<?php

namespace Booni3\AmazonShipping\Tests;

use Orchestra\Testbench\TestCase;
use Booni3\AmazonShipping\AmazonShippingServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [AmazonShippingServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
