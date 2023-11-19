<?php 

namespace App\Controllers\Api;

use App\Models\User;

use App\Core\Request;
use App\Core\Response;
use App\Core\Controller;
use App\Core\Validation;

use App\Actions\User\UpdatePasswordAction;
use App\Actions\User\UpdateInformationsAction;

use App\Middlewares\VerifyTokenMiddleware;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->registerMiddleware(
            [
                "class" => VerifyTokenMiddleware::class,
                "actions" => ["index", "update", "updatePassword"]
            ]
        );
    }

	public function index(Request $request, Response $response)
	{
		return $response->jsonResponse([
			"success" => true,
			"user" => $request->user()
		], 200);
	}

    public function update(Request $request, Response $response)
    {
		$errors = Validation::validate($request, [
			"name" => "required",
			"surname" => "required",
			"email" => $request->user()->email !== $request->body["email"] ? 
							"required|email|unique:email" : 
							"required|email",
			"username" => $request->user()->username !== $request->body["username"] ? 
							"required|unique:username" : 
							"required",
		], User::class);

		if(empty($errors)) {
			if($token = (new UpdateInformationsAction())->execute(
				$request->body["name"],
				$request->body["surname"],
				$request->body["username"],
				$request->body["email"],
				$request->user()->id
			)) {
				return $response->jsonResponse([
					"success" => true,
					"message" => "Votre profil a bien été modifié.",
					"token" => $token
				], 200);
			}			
		} else {
			return $response->jsonResponse([
				'success' => false,
				'errors' => $errors
			], 422);
		}
	}

	public function updatePassword(Request $request, Response $response)
	{
		$errors = Validation::validate($request, [
			'password' => 'required',
			'newPassword' => 'required|min:8',
			'newPassword_confirmation' => 'required|confirmed'
		], User::class);

		if(empty($errors)) {
			if((new UpdatePasswordAction())->execute(
				$request->body['password'],
				$request->body['newPassword'],
				$request->user()
			)) {
				return $response->jsonResponse([
					'success' => true,
					'message' => 'Votre mot de passe a bien été modifié.'
				], 200);
			} else {
				return $response->jsonResponse([
					'success' => false,
					'errors' => [
						'password' => 'Le mot de passe est incorrect.'
					]
				], 422);
			}
		} else {
			return $response->jsonResponse([
				'success' => false,
				'errors' => $errors
			], 422);
		}

	}
	
}
