<?php 
App\Core\Application::get('/', [App\Controllers\SiteController::class, 'landingPage']);

App\Core\Application::get('/me', [App\Controllers\SiteController::class, 'homepage']);

App\Core\Application::get('/contacts/{id}', [App\Controllers\SiteController::class, 'show']);
App\Core\Application::get('/contacts/create', [App\Controllers\SiteController::class, 'create']);

App\Core\Application::get('/profile', [App\Controllers\SiteController::class, 'profile']);


App\Core\Application::get('/login', [App\Controllers\SiteController::class, 'login']);
App\Core\Application::get('/register', [App\Controllers\SiteController::class, 'register']);

App\Core\Application::get('/forgot-password', [App\Controllers\SiteController::class, 'forgotPassword']);
App\Core\Application::get('/404', fn() =>  '404 Not Found');
