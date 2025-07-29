<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

// Define signal constants for Windows compatibility (before Laravel boots)
if (PHP_OS_FAMILY === 'Windows') {
    if (!defined('SIGINT')) define('SIGINT', 2);
    if (!defined('SIGTERM')) define('SIGTERM', 15);
    if (!defined('SIGHUP')) define('SIGHUP', 1);
    if (!defined('SIGUSR1')) define('SIGUSR1', 10);
    if (!defined('SIGUSR2')) define('SIGUSR2', 12);
    if (!defined('SIGKILL')) define('SIGKILL', 9);
    if (!defined('SIGQUIT')) define('SIGQUIT', 3);
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'subscribed' => \App\Http\Middleware\CheckModuleSubscription::class,
            'tenant.ensure' => \App\Http\Middleware\EnsureTenantMiddleware::class,
            'super.admin.switch' => \App\Http\Middleware\SuperAdminCompanySwitcher::class,
        ]);

        // Apply tenant middleware globally untuk web routes
        $middleware->web(append: [
            \App\Http\Middleware\SuperAdminCompanySwitcher::class, // Harus sebelum EnsureTenantMiddleware
            \App\Http\Middleware\EnsureTenantMiddleware::class,
        ]);

        // $middleware->append(RoleMiddleware::class);
    })
    ->withCommands([
        \App\Console\Commands\StartFrankenPhpWindows::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
