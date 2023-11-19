<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Core\Application;

class LoginAction {

	/**
	 * The function takes an email and password as input, checks if the user exists and if the password is
	 * correct, and returns an array with error messages or sets the user in the application.
	 * 
	 * @param string email The email parameter is a string that represents the user's email address.
	 * @param string password The password parameter is a string that represents the user's password.
	 * 
	 * @return array|null an array or null.
	 */
	public function execute(
		string $email,
		string $password
	): array|string
	{
		$user = User::find(['email' => $email]);
    
        if(!$user) {
			return [
				'errors' => [
					'email' => 'Aucun compte n\'est associé à cet email'
				]
			];	
		}

		if(!password_verify($password, $user->password)) {
			return [
				'errors' => [
					'password' => 'Mot de passe incorrect'
				]
			];
		}
        
		return Application::$app->generateJwtToken($user);              
	}
}