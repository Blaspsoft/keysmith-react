<?php

namespace Blaspsoft\KeysmithReact\Console;

use Illuminate\Filesystem\Filesystem;

trait InstallSettingsView
{
    public function installSettingsView()
    {
        // Composer packages
        if (! $this->requireComposerPackages(['inertiajs/inertia-laravel:^2.0', 'laravel/sanctum:^4.0'])) {
            return 1;
        }

        // Controller
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers/Settings'));
        (new Filesystem)->copy(__DIR__.'/../../stubs/app/Http/Controllers/Settings/TokenController.php', app_path('Http/Controllers/Settings/TokenController.php'));

        // Views
        (new Filesystem)->ensureDirectoryExists(resource_path('js/pages/settings'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/pages/settings', resource_path('js/pages/settings'));

        // Components
        (new Filesystem)->ensureDirectoryExists(resource_path('js/components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/components', resource_path('js/components'));

        // Tests
        (new Filesystem)->ensureDirectoryExists(base_path('tests/Feature/ApiToken'));
        (new Filesystem)->copy(__DIR__.'/../../stubs/tests/Feature/ApiToken/SettingsTokenTest.php', base_path('tests/Feature/ApiToken/SettingsTokenTest.php'));
    }
}