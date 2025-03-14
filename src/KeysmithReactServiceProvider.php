<?php

namespace Blaspsoft\KeysmithReact;

use Illuminate\Support\Facades\Route;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class KeysmithReactServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if (!$this->app->routesAreCached()) {
            Route::middleware(['web', 'auth'])
                ->group(function () {
                    require __DIR__.'/../routes/web.php';
                });
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('keysmith.php'),
            ], 'keysmith-config');

            (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers/APITokens'));
            (new Filesystem)->copy(__DIR__.'/../stubs/app/Http/Controllers/APITokens/TokenController.php', app_path('Http/Controllers/APITokens/TokenController.php'));
        }

        $this->commands([
            Console\InstallCommand::class,
        ]);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $filesystem = new Filesystem();

        // Dynamically bind the correct controller based on its presence
        $controllers = [
            'App\Http\Controllers\APITokens\TokenController' => app_path('Http/Controllers/APITokens/TokenController.php'),
        ];

        foreach ($controllers as $class => $path) {
            if ($filesystem->exists($path)) {
                $this->app->bind($class);
                break;
            }
        }
        
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'keysmith');

        return [Console\InstallCommand::class];
    }
}
