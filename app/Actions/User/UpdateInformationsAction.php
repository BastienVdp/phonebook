<?php

namespace App\Actions\User;

use App\Models\User;
use App\Core\Application;

class UpdateInformationsAction
{
    public function execute(
        string $name,
        string $surname,
        string $username,
        string $email,
        int $user_id
    ) {
        $user = User::update(
            ["id" => $user_id],
            [
             "name"     => $name,
             "surname"  => $surname,
             "username" => $username,
             "email"    => $email,
            ]
        );

        $token = Application::$app->generateJwtToken($user);

        return $token;
    }
}
