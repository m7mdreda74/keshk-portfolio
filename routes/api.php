<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Middleware\AdminAuthenticated;

// Public portfolio routes
Route::get('/portfolio-data', [PortfolioController::class, 'index']);
Route::post('/contact', [ContactController::class, 'store']);

// Admin auth (no middleware on these)
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/logout', [AdminController::class, 'logout']);
Route::get('/admin/check-auth', [AdminController::class, 'checkAuth']);

// Admin protected routes
Route::middleware([AdminAuthenticated::class])->prefix('admin')->group(function () {

    // Personal Info
    Route::get('/personal-info', [AdminController::class, 'getPersonalInfo']);
    Route::put('/personal-info', [AdminController::class, 'updatePersonalInfo']);

    // Skills
    Route::get('/skills', [AdminController::class, 'getSkills']);
    Route::post('/skills', [AdminController::class, 'createSkill']);
    Route::put('/skills/{skill}', [AdminController::class, 'updateSkill']);
    Route::delete('/skills/{skill}', [AdminController::class, 'deleteSkill']);

    // Services
    Route::get('/services', [AdminController::class, 'getServices']);
    Route::post('/services', [AdminController::class, 'createService']);
    Route::put('/services/{service}', [AdminController::class, 'updateService']);
    Route::delete('/services/{service}', [AdminController::class, 'deleteService']);

    // Stats
    Route::get('/stats', [AdminController::class, 'getStats']);
    Route::post('/stats', [AdminController::class, 'createStat']);
    Route::put('/stats/{stat}', [AdminController::class, 'updateStat']);
    Route::delete('/stats/{stat}', [AdminController::class, 'deleteStat']);

    // Projects
    Route::get('/projects', [AdminController::class, 'getProjects']);
    Route::post('/projects', [AdminController::class, 'createProject']);
    Route::put('/projects/{project}', [AdminController::class, 'updateProject']);
    Route::delete('/projects/{project}', [AdminController::class, 'deleteProject']);

    // Resume Items
    Route::get('/resume-items', [AdminController::class, 'getResumeItems']);
    Route::post('/resume-items', [AdminController::class, 'createResumeItem']);
    Route::put('/resume-items/{resumeItem}', [AdminController::class, 'updateResumeItem']);
    Route::delete('/resume-items/{resumeItem}', [AdminController::class, 'deleteResumeItem']);

    // Testimonials
    Route::get('/testimonials', [AdminController::class, 'getTestimonials']);
    Route::post('/testimonials', [AdminController::class, 'createTestimonial']);
    Route::put('/testimonials/{testimonial}', [AdminController::class, 'updateTestimonial']);
    Route::delete('/testimonials/{testimonial}', [AdminController::class, 'deleteTestimonial']);

    // Contact Messages
    Route::get('/messages', [AdminController::class, 'getMessages']);
    Route::delete('/messages/{contactMessage}', [AdminController::class, 'deleteMessage']);
});

