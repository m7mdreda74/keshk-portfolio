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
    $xml = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
  <url>
    <loc>https://keshk-portfolio.vercel.app/</loc>
    <lastmod>' . date('Y-m-d') . '</lastmod>
    <changefreq>weekly</changefreq>
    <priority>1.0</priority>
    <image:image>
      <image:loc>https://keshk-portfolio.vercel.app/assets/img/hero-img.png</image:loc>
      <image:title>Mohamed Keshk — Backend Developer Portfolio</image:title>
    </image:image>
    <image:image>
      <image:loc>https://keshk-portfolio.vercel.app/assets/img/profile-img.png</image:loc>
      <image:title>Mohamed Keshk Profile Photo</image:title>
    </image:image>
  </url>
</urlset>';

    return response($xml, 200)
        ->header('Content-Type', 'application/xml; charset=UTF-8')
        ->header('X-Robots-Tag', 'noindex');
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

