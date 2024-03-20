<?php

namespace Ibra\ApiKey\Providers;

use Ibra\ApiKey\Console\Commands\DeactivateApiKey;
use Ibra\ApiKey\Console\Commands\GenerateApiKey;
use Ibra\ApiKey\Console\Commands\ListApiKeys;
use Ibra\ApiKey\Console\Commands\RemoveApiKey;
use Ibra\ApiKey\Middlewares\ApiKeyMiddleware;
use Illuminate\Support\ServiceProvider;

class ApiKeyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/api_key.php' => config_path('api_key.php')
        ], 'api_key');

        $this->mergeConfigFrom(__DIR__ . '/../../config/api_key.php', 'api_key');

        app('router')->aliasMiddleware('simple_api_key', ApiKeyMiddleware::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateApiKey::class,
                RemoveApiKey::class,
                ListApiKeys::class,
                DeactivateApiKey::class
            ]);
        }
    }
}
