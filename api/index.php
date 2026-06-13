<?php

// If running on Vercel, prepare writeable storage directories in /tmp
if (isset($_ENV['VERCEL'])) {
    $storageDirs = [
        '/tmp/storage/framework/views',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/cache',
        '/tmp/storage/bootstrap/cache',
        '/tmp/storage/logs',
    ];
    foreach ($storageDirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    // Redirect cache files to /tmp/storage to bypass read-only filesystem limitations
    $_ENV['APP_SERVICES_CACHE'] = '/tmp/storage/bootstrap/cache/services.php';
    $_ENV['APP_PACKAGES_CACHE'] = '/tmp/storage/bootstrap/cache/packages.php';
    $_ENV['APP_CONFIG_CACHE'] = '/tmp/storage/bootstrap/cache/config.php';
    $_ENV['APP_ROUTES_CACHE'] = '/tmp/storage/bootstrap/cache/routes.php';
    
    // Override SCRIPT_NAME to prevent Laravel from stripping /api/ prefix from routes
    $_SERVER['SCRIPT_NAME'] = '/index.php';
}

// Forward requests to public/index.php for Laravel
require __DIR__ . '/../public/index.php';
