<?php

declare(strict_types=1);

/**
 * PHPUnit Bootstrap File
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Define BASE_PATH for tests
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// Set up environment for tests
$_ENV['APP_ENV'] = 'testing';

// Create runtime directories for tests
$runtimeDirs = [
    BASE_PATH . '/runtime',
    BASE_PATH . '/runtime/cache',
    BASE_PATH . '/runtime/cache/doctrine',
    BASE_PATH . '/runtime/proxies',
];

foreach ($runtimeDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}