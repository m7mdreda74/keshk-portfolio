<?php

use Illuminate\Support\Facades\Route;

Route::get('/run-migrations-and-seed-db', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
            '--force' => true,
            '--seed' => true,
        ]);
        return 'Database migrated and seeded successfully!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/db-test', function () {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        return 'Database connection is working fine!';
    } catch (\Exception $e) {
        return 'Database connection error: ' . $e->getMessage();
    }
});

Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');
