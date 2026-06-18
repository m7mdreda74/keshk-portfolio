<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\PersonalInfoController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\StatController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ResumeItemController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Middleware\AdminAuthenticated;

// ──────────────────────────────────────────
//  Public portfolio routes
// ──────────────────────────────────────────
Route::get('/portfolio-data', [PortfolioController::class, 'index']);
Route::post('/contact',       [ContactController::class,  'store']);

// ──────────────────────────────────────────
//  Admin auth (no middleware)
// ──────────────────────────────────────────
Route::post('/admin/login',      [AuthController::class, 'login']);
Route::post('/admin/logout',     [AuthController::class, 'logout']);
Route::get('/admin/check-auth',  [AuthController::class, 'checkAuth']);

// ──────────────────────────────────────────
//  Admin protected routes
// ──────────────────────────────────────────
Route::middleware([AdminAuthenticated::class])->prefix('admin')->group(function () {

    // Personal Info
    Route::get('/personal-info', [PersonalInfoController::class, 'show']);
    Route::put('/personal-info', [PersonalInfoController::class, 'update']);

    // Skills
    Route::get('/skills',           [SkillController::class, 'index']);
    Route::post('/skills',          [SkillController::class, 'store']);
    Route::put('/skills/{skill}',   [SkillController::class, 'update']);
    Route::delete('/skills/{skill}',[SkillController::class, 'destroy']);

    // Services
    Route::get('/services',              [ServiceController::class, 'index']);
    Route::post('/services',             [ServiceController::class, 'store']);
    Route::put('/services/{service}',    [ServiceController::class, 'update']);
    Route::delete('/services/{service}', [ServiceController::class, 'destroy']);

    // Stats
    Route::get('/stats',          [StatController::class, 'index']);
    Route::post('/stats',         [StatController::class, 'store']);
    Route::put('/stats/{stat}',   [StatController::class, 'update']);
    Route::delete('/stats/{stat}',[StatController::class, 'destroy']);

    // Projects
    Route::get('/projects',              [ProjectController::class, 'index']);
    Route::post('/projects',             [ProjectController::class, 'store']);
    Route::put('/projects/{project}',    [ProjectController::class, 'update']);
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);

    // Resume Items
    Route::get('/resume-items',                [ResumeItemController::class, 'index']);
    Route::post('/resume-items',               [ResumeItemController::class, 'store']);
    Route::put('/resume-items/{resumeItem}',   [ResumeItemController::class, 'update']);
    Route::delete('/resume-items/{resumeItem}',[ResumeItemController::class, 'destroy']);

    // Testimonials
    Route::get('/testimonials',                  [TestimonialController::class, 'index']);
    Route::post('/testimonials',                 [TestimonialController::class, 'store']);
    Route::put('/testimonials/{testimonial}',    [TestimonialController::class, 'update']);
    Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy']);

    // Contact Messages
    Route::get('/messages',                        [MessageController::class, 'index']);
    Route::delete('/messages/{contactMessage}',    [MessageController::class, 'destroy']);
});
