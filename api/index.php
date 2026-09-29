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

    // Auto-configure TiDB Cloud MySQL (overrides dead Aiven host)
    $currentHost = $_ENV['DB_HOST'] ?? ($_SERVER['DB_HOST'] ?? getenv('DB_HOST'));
    if (!$currentHost || str_contains($currentHost, 'aivencloud.com') || $currentHost === '127.0.0.1') {
        $dbConfig = [
            'DB_CONNECTION' => 'mysql',
            'DB_HOST' => 'gateway01.ap-northeast-1.prod.aws.tidbcloud.com',
            'DB_PORT' => '4000',
            'DB_DATABASE' => 'keshk_portfolio',
            'DB_USERNAME' => 'qvR1wimWkkNFir9.root',
            'DB_PASSWORD' => 'tzZ3Dt9R7FMabxfa',
            'MYSQL_ATTR_SSL_CA' => 'true',
            'MYSQL_ATTR_SSL_VERIFY_SERVER_CERT' => 'false',
        ];

        foreach ($dbConfig as $key => $val) {
            $_ENV[$key] = $val;
            $_SERVER[$key] = $val;
            putenv("{$key}={$val}");
        }
    }
}

// Forward requests to public/index.php for Laravel
require __DIR__ . '/../public/index.php';
