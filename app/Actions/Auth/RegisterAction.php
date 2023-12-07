<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Core\Application;

class RegisterAction
{
    /**
     * The function executes a user registration process by creating a new user with the provided email,
     * username, and password.
     *
     * @param string email The email parameter is a string that represents the user's email address. It is
     * used to create a new user object with the specified email.
     * @param string username The username parameter is a string that represents the username of the user.
     * It is used to identify the user and is typically unique for each user in the system.
     * @param string password The password parameter is a string that represents the user's password.
     */
    public function execute(
        string $email,
        string $username,
        string $name,
        string $surname,
        string $password
    ) {
        $user = User::create(
            [
             'email'    => $email,
             'username' => $username,
             'name'     => $name,
             'surname'  => $surname,
             'password' => password_hash($password, PASSWORD_DEFAULT),
            ]
        );

        return Application::$app->generateJwtToken($user);
    }
}
