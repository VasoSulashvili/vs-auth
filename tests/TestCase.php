<?php

namespace VS\Auth\Tests;

//use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Orchestra\Testbench\TestCase as BaseTestCase;
use VS\Auth\VSAuthServiceProvider;


abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            VSAUTHServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Example: Setting up migrations for tests
        $app['config']->set('database.default', 'testing');
    }
}
