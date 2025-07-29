<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class WindowsCompatibilityServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Define signal constants for Windows compatibility
        if (PHP_OS_FAMILY === 'Windows') {
            $this->defineSignalConstants();
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Define signal constants if they don't exist (Windows compatibility)
     */
    private function defineSignalConstants(): void
    {
        if (!defined('SIGINT')) {
            define('SIGINT', 2);
        }

        if (!defined('SIGTERM')) {
            define('SIGTERM', 15);
        }

        if (!defined('SIGHUP')) {
            define('SIGHUP', 1);
        }

        if (!defined('SIGUSR1')) {
            define('SIGUSR1', 10);
        }

        if (!defined('SIGUSR2')) {
            define('SIGUSR2', 12);
        }

        if (!defined('SIGKILL')) {
            define('SIGKILL', 9);
        }

        if (!defined('SIGQUIT')) {
            define('SIGQUIT', 3);
        }

        // Log for debugging
        if (config('app.debug')) {
            logger('Windows signal constants defined', [
                'SIGINT' => SIGINT,
                'SIGTERM' => SIGTERM,
                'SIGHUP' => SIGHUP,
            ]);
        }
    }
}
