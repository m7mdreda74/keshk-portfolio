<?php

use Illuminate\Support\Facades\Route;

// Admin dashboard SPA — must be defined before the catch-all
Route::get('/admin', function () {
    return view('admin');
});
Route::get('/admin/{any}', function () {
    return view('admin');
})->where('any', '.*');

// Serve sitemap.xml with correct XML Content-Type
Route::get('/sitemap.xml', function () {
    $path = public_path('sitemap.xml');
    return response(file_get_contents($path), 200)
        ->header('Content-Type', 'application/xml; charset=UTF-8');
});

// Serve robots.txt as plain text
Route::get('/robots.txt', function () {
    $path = public_path('robots.txt');
    return response(file_get_contents($path), 200)
        ->header('Content-Type', 'text/plain');
});

// Portfolio SPA catch-all
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');

