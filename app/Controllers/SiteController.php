<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Controller;

class SiteController extends Controller
{
    public function landingPage()
    {
        return View::make('LandingPageView', layout: 'no-layout');
    }

    public function homepage()
    {
        return View::make('HomeView');
    }

    public function show()
    {

        return View::make('ShowContactView');
    }

    public function create()
    {
        return View::make('AddContactView');
    }

    public function profile()
    {
        return View::make('ProfileView');
    }

    public function login()
    {
        return View::make('LoginView');
    }

    public function register()
    {
        return View::make('RegisterView');
    }

    public function forgotPassword()
    {
        return View::make('ForgotPasswordView');
    }
}
