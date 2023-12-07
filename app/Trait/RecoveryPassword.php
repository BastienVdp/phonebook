<?php

namespace App\Trait;

use App\Core\Validation;
use App\Models\User;
use App\Models\Question;

trait RecoveryPassword
{
    public function checkEmail($request, $response)
    {
        $errors = Validation::validate(
            $request,
            ['email' => 'required|email'],
            User::class
        );

        if ($errors) {
            return $response->jsonResponse(
                [
                 'success' => false,
                 'errors'  => $errors,
                ],
                422
            );
        } else {
            $user = User::find(['email' => $request->body['email']]);
            if (!$user) {
                return $response->jsonResponse(
                    [
                     'success' => false,
                     'errors'  => ['email' => 'Cette adresse e-mail n\'existe pas'],
                    ],
                    422
                );
            } elseif (empty($user->questions())) {
                return $response->jsonResponse(
                    [
                     'success' => false,
                     'errors'  => ['email' => 'Aucune question de sécurité n\'est associée à cette adresse e-mail'],
                    ],
                    422
                );
            } else {
                $token = bin2hex(random_bytes(32));
                User::update(
                    ['email' => $request->body['email']],
                    ['reset_token' => $token]
                );
                return $response->jsonResponse(
                    [
                     'success'   => true,
                     'questions' => $user->questions(),
                     'token'     => $token,
                    ],
                    200
                );
            }
        }
    }

    public function checkQuestion($request, $response)
    {
        $errors = Validation::validate(
            $request,
            [
             'email'    => 'required|email',
             'question' => 'required',
             'reponse'  => 'required',
            ],
            Question::class
        );

        if (empty($errors)) {
            if (
                Question::find(
                    [
                    'id'      => $request->body['question'],
                    'user_id' => User::find(['email' => $request->body['email']])->id,
                    'reponse' => $request->body['reponse'],
                    ]
                )
            ) {
                return $response->jsonResponse(
                    ['success' => true],
                    200
                );
            } else {
                return $response->jsonResponse(
                    [
                     'success' => false,
                     'errors'  => ['reponse' => 'La réponse est incorrecte'],
                    ],
                    422
                );
            }
        } else {
            return $response->jsonResponse(
                [
                 'success' => false,
                 'errors'  => $errors,
                ],
                422
            );
        }
    }

    public function resetPassword($request, $response)
    {
        $errors = Validation::validate(
            $request,
            [
             'email'                    => 'required|email',
             'newPassword'              => 'required|min:4',
             'newPassword_confirmation' => 'required|confirmed',
            ],
            User::class
        );

        if (empty($errors)) {
            $user = User::find(['email' => $request->body['email']]);

            if ($request->body['token'] === $user->reset_token) {
                User::update(
                    [
                     'email' => $request->body['email'],
                    ],
                    [
                     'password' => password_hash($request->body['newPassword_confirmation'], PASSWORD_DEFAULT),
                    ]
                );
                return $response->jsonResponse(
                    [
                     'success' => true,
                     'message' => 'Votre mot de passe a bien été modifié',
                    ],
                    200
                );
            } else {
                return $response->jsonResponse(
                    [
                     'success' => false,
                     'token'   => 'Le token est incorrect',
                    ],
                    422
                );
            }
        } else {
            return $response->jsonResponse(
                [
                 'success' => false,
                 'errors'  => $errors,
                ],
                422
            );
        }
    }
}
