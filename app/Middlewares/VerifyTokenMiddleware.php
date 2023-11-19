<?php 

namespace App\Middlewares;

use App\Core\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class VerifyTokenMiddleware extends Middleware
{
	private string $key = 'gB8Mb1GrBYnK6ZuHahPtWgVFGj3wLytr';

    public function execute($request): void
    {
        $token = getallheaders()['Authorization'] ?? null;

		if(!$token) {
			echo json_encode([
				'message' => 'Authentication failed.',
				'token' => $token
			]);
			http_response_code(401);
			exit;
		}

		try {

			$token = str_replace('Bearer ', '', $token);
			$jwt = JWT::decode($token, new Key($this->key, 'HS256'));
			$request->merge(['user' => $jwt->user]);
		
		} catch(\Exception $e) {
			echo json_encode(array('message' => 'Authentication failed.', 'errors' => $e->getMessage()));
			http_response_code(401);
		}
	}
}
