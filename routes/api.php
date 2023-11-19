<?php 

App\Core\Application::post('/api/login', [App\Controllers\Api\AuthController::class, 'login']);
App\Core\Application::post('/api/register', [App\Controllers\Api\AuthController::class, 'register']);
App\Core\Application::post('/api/check-email', [App\Controllers\Api\AuthController::class, 'checkEmail']);
App\Core\Application::post('/api/check-question', [App\Controllers\Api\AuthController::class, 'checkQuestion']);
App\Core\Application::post('/api/reset-password', [App\Controllers\Api\AuthController::class, 'resetPassword']);

App\Core\Application::get('/api/contacts', [App\Controllers\Api\ContactsController::class, 'index']);
App\Core\Application::get('/api/contacts/{id}', [App\Controllers\Api\ContactsController::class, 'show']);
App\Core\Application::post('/api/contacts/edit/{id}', [App\Controllers\Api\ContactsController::class, 'update']);
App\Core\Application::post('/api/contacts', [App\Controllers\Api\ContactsController::class, 'store']);
App\Core\Application::delete('/api/contacts/{id}', [App\Controllers\Api\ContactsController::class, 'delete']);

App\Core\Application::get('/api/profile', [App\Controllers\Api\ProfileController::class, 'index']);
App\Core\Application::post('/api/profile', [App\Controllers\Api\ProfileController::class, 'update']);
App\Core\Application::post('/api/profile/password', [App\Controllers\Api\ProfileController::class, 'updatePassword']);
