<?php
/**
 * FrankenPHP Windows Starter
 * This script helps start FrankenPHP on Windows with proper signal handling
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "FrankenPHP Windows Starter\n";
echo "=========================\n\n";

// Define signal constants if they don't exist (Windows compatibility)
if (!defined('SIGINT')) {
    define('SIGINT', 2);
    echo "Defined SIGINT: " . SIGINT . "\n";
}

if (!defined('SIGTERM')) {
    define('SIGTERM', 15);
    echo "Defined SIGTERM: " . SIGTERM . "\n";
}

if (!defined('SIGHUP')) {
    define('SIGHUP', 1);
    echo "Defined SIGHUP: " . SIGHUP . "\n";
}

// Additional constants
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

echo "\nSignal constants initialized successfully.\n";
echo "Starting Laravel Octane with FrankenPHP server...\n\n";

// Check if running on Windows
$isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
echo "Operating System: " . ($isWindows ? "Windows" : "Unix/Linux") . "\n";

// Check PHP version
$phpVersion = PHP_VERSION;
echo "PHP Version: " . $phpVersion . "\n";

if (version_compare($phpVersion, '8.0.0', '<')) {
    echo "Error: PHP 8.0 or higher is required for FrankenPHP.\n";
    exit(1);
}

// Check if Laravel Octane is installed
$composerPath = __DIR__ . '/../vendor/laravel/octane';
if (!is_dir($composerPath)) {
    echo "Error: Laravel Octane not found. Please run 'composer install'.\n";
    exit(1);
}

echo "Laravel Octane found.\n";

// Check if .env file exists
$envPath = __DIR__ . '/../.env';
if (!file_exists($envPath)) {
    echo "Warning: .env file not found. Creating from .env.example...\n";
    $envExamplePath = __DIR__ . '/../.env.example';
    if (file_exists($envExamplePath)) {
        copy($envExamplePath, $envPath);
        echo ".env file created successfully.\n";
    } else {
        echo "Error: .env.example not found.\n";
        exit(1);
    }
}

// Update .env to use FrankenPHP
$envContent = file_get_contents($envPath);
if (strpos($envContent, 'OCTANE_SERVER=frankenphp') === false) {
    if (strpos($envContent, 'OCTANE_SERVER=') !== false) {
        $envContent = preg_replace('/OCTANE_SERVER=.*/', 'OCTANE_SERVER=frankenphp', $envContent);
    } else {
        $envContent .= "\nOCTANE_SERVER=frankenphp\n";
    }
    file_put_contents($envPath, $envContent);
    echo "Updated .env to use FrankenPHP server.\n";
}

echo "\nStarting FrankenPHP server on http://localhost:8000\n";
echo "Press Ctrl+C to stop the server.\n";
echo "----------------------------------------\n\n";

// Change to Laravel root directory
chdir(__DIR__ . '/..');

// Build the command
$command = 'php artisan octane:start --server=frankenphp --port=8000';

// Add --no-reload flag for Windows to avoid signal issues
if ($isWindows) {
    $command .= ' --no-reload';
    echo "Note: Running with --no-reload flag for Windows compatibility.\n";
    echo "For hot reloading, consider using Docker or WSL.\n\n";
}

// Execute the command
echo "Executing: $command\n\n";
passthru($command, $returnCode);

if ($returnCode !== 0) {
    echo "\nFrankenPHP failed to start. Trying alternative methods...\n";

    // Try with RoadRunner as fallback
    echo "Attempting to start with RoadRunner server...\n";
    $fallbackCommand = 'php artisan octane:start --server=roadrunner --port=8000';
    if ($isWindows) {
        $fallbackCommand .= ' --no-reload';
    }

    passthru($fallbackCommand, $fallbackReturnCode);

    if ($fallbackReturnCode !== 0) {
        echo "\nBoth FrankenPHP and RoadRunner failed to start.\n";
        echo "Please check:\n";
        echo "1. PHP version is 8.0+\n";
        echo "2. Required PHP extensions are installed\n";
        echo "3. Laravel Octane is properly installed\n";
        echo "4. Port 8000 is not in use\n";
        echo "\nAlternatively, try using Docker: docker-compose up -d\n";
        exit(1);
    }
}

echo "\nServer stopped.\n";
