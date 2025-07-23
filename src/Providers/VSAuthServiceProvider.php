<?php

declare(strict_types=1);

namespace VSAuth\Providers;

use Illuminate\Support\ServiceProvider;
use VSApi\Traits\HasDeepMerge;

class VSAuthServiceProvider extends ServiceProvider
{
    use HasDeepMerge;

    public function register()
    {
        $this->mergeDeepConfigFromPath(
            'auth',
            __DIR__ . '/../../configs/vs-auth-driver.php'
        );
    }

    public function boot()
    {
        // Load Routes
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');

        // Load Migrations
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

    }

}
