<?php

use Illuminate\Support\Facades\Route;

// Admin dashboard SPA — must be defined before the catch-all
Route::get('/admin', function () {
    return view('admin');
});
Route::get('/admin/{any}', function () {
    return view('admin');
})->where('any', '.*');

// Portfolio SPA catch-all
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');
