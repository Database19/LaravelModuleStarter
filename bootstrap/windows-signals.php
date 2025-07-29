<?php
/**
 * Windows Signal Constants Helper
 * This file defines signal constants that may not be available on Windows
 */

// Define signal constants if they don't exist (Windows compatibility)
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

// Additional constants that might be needed
if (!defined('SIGKILL')) {
    define('SIGKILL', 9);
}

if (!defined('SIGQUIT')) {
    define('SIGQUIT', 3);
}

echo "Signal constants defined for Windows compatibility.\n";
echo "SIGINT: " . SIGINT . "\n";
echo "SIGTERM: " . SIGTERM . "\n";
echo "SIGHUP: " . SIGHUP . "\n";
