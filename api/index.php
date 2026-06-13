<?php

// If running on Vercel, prepare writeable storage directories in /tmp
if (isset($_ENV['VERCEL'])) {
    $storageDirs = [
        '/tmp/storage/framework/views',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/cache',
        '/tmp/storage/bootstrap/cache',
    ];
    foreach ($storageDirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}

// Forward requests to public/index.php for Laravel
require __DIR__ . '/../public/index.php';
