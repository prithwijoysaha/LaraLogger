<?php

namespace prithwijoysaha\laralogger\LaraLogger;

use Illuminate\Support\ServiceProvider;

class LaraLoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->mergeConfigFrom(
            __DIR__ . '/config/laralogger.php',
            'laralogger'
        );

        $this->publishes([
            __DIR__ . '/config/laralogger.php' => config_path('laralogger.php')
        ], 'laralogger-config');

        $this->publishes([
            __DIR__ . '/database/migrations/' => database_path('migrations')
        ], 'laralogger-migrations');
    }

    public function register()
    {
    }
}
