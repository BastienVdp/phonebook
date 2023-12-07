<?php 

namespace Database\Factories;

use App\Models\User;
use App\Core\Factory;

class UserFactory extends Factory
{
	public static $model = User::class;

	public static function generate($dependencies, $overrides): array
	{	
		return array_replace([
			'username' => 'Admin',
			'name' => 'Admin',
			'surname' => 'Admin',
			'email' => 'admin@admin.be',
			'password' => password_hash('password', PASSWORD_DEFAULT),
			'admin' => 0,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		], $overrides);
	}
}