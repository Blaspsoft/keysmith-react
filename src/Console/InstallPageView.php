<?php

namespace Blaspsoft\KeysmithReact\Console;

use Illuminate\Filesystem\Filesystem;

trait InstallPageView
{
    public function installPageView()
    {
        // Composer packages
        if (! $this->requireComposerPackages(['inertiajs/inertia-laravel:^2.0', 'laravel/sanctum:^4.0'])) {
            return 1;
        }

        // Controller
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers/APITokens'));
        (new Filesystem)->copy(__DIR__.'/../../stubs/app/Http/Controllers/APITokens/TokenController.php', app_path('Http/Controllers/APITokens/TokenController.php'));

        // Views
        (new Filesystem)->ensureDirectoryExists(resource_path('js/pages/api-tokens'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/pages/api-tokens', resource_path('js/pages/api-tokens'));

        // Components
        (new Filesystem)->ensureDirectoryExists(resource_path('js/components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/components', resource_path('js/components'));

        // Tests
        (new Filesystem)->ensureDirectoryExists(base_path('tests/Feature/ApiToken'));
        (new Filesystem)->copy(__DIR__.'/../../stubs/tests/Feature/ApiToken/PageTokenTest.php', base_path('tests/Feature/ApiToken/PageTokenTest.php'));
    }
}