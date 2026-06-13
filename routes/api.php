<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\ContactController;

Route::get('/portfolio-data', [PortfolioController::class, 'index']);
Route::post('/contact', [ContactController::class, 'store']);
