<?php

namespace App\Actions\User;

use App\Models\User;

class UpdatePasswordAction
{
    public function execute(
        string $password,
        string $newPassword,
        $user
    ) {
        if (!password_verify($password, $user->password)) {
            return false;
        } else {
            User::update(
                [
                 'id' => $user->id,
                ],
                [
                 'password' => password_hash($newPassword, PASSWORD_DEFAULT),
                ]
            );

            return true;
        }
    }
}
