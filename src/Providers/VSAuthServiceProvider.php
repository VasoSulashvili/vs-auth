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
//        $this->merge(realpath(__DIR__ . '/../../configs/vs-auth-driver.php'), config_path('auth.php'), 'guards');

        $this->mergeDeepConfigFromPath(
            'auth',
            __DIR__ . '/../../configs/vs-auth-driver.php'
        );

    }

    public function boot()
    {

    }

}
