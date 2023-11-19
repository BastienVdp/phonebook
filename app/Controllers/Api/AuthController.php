<?php 

namespace App\Controllers\Api;

use App\Models\User;
use App\Core\Request;
use App\Core\Controller;


use App\Core\Validation;
use App\Trait\RecoveryPassword;
use App\Actions\Auth\LoginAction;
use App\Actions\Auth\RegisterAction;
use App\Core\Response;

class AuthController extends Controller
{
    use RecoveryPassword;

    public function login(Request $request, Response $response)
    {
        $errors = Validation::validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ], User::class);

        if(empty($errors)) {
            if($callback = (new LoginAction())->execute(
                $request->body['email'],
                $request->body['password']
            )) {
                if(!isset($callback['errors'])) {
                    return $response->jsonResponse([
                        'success' => true,
                        'token' => $callback
                    ], 200);
                } else {
                    return $response->jsonResponse([
                        'success' => false,
                        'errors' => $callback['errors']
                    ], 422);
                }
            }
        } else {
            return $response->jsonResponse([
                'success' => false,
                'errors' => $errors
            ], 422);
        }    
    }

    public function register(Request $request, Response $response)
    {
        $errors = Validation::validate($request, [
            'username' => 'required|min:3|unique:username',
            'email' => 'required|email|unique:email',
            'name' => 'required|min:3',
            'surname' => 'required|min:3',
            'password' => 'required|min:4',
            'password_confirmation' => 'required|confirmed'
        ], User::class);

        if(empty($errors)) {
            $token = (new RegisterAction())->execute(
                $request->body['email'],
                $request->body['username'],
                $request->body['name'],
                $request->body['surname'],
                $request->body['password']
            );
            return $response->jsonResponse([
                'success' => true,
                'token' => $token
            ], 200);
        } else {
            return $response->jsonResponse([
                'success' => false,
                'errors' => $errors
            ], 422);
        }
    }
}
