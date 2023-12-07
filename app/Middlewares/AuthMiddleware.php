<?php

namespace App\Middlewares;

use App\Core\View;
use App\Core\Middleware;
use App\Core\Application;

class AuthMiddleware extends Middleware
{
    public function execute($request): void
    {
        if (View::isCurrentPath('/login') || View::isCurrentPath('/register')) {
            if (Application::isConnected()) {
                Application::$app->response->redirect('/');
            }
        } else {
            if (!Application::isConnected()) {
                Application::$app->response->redirect('/login');
            }
        }
    }
}
