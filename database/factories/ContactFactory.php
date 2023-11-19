<?php 

namespace Database\Factories;

use App\Models\Contact;
use App\Core\Factory;

class ContactFactory extends Factory
{
	public static $model = Contact::class;

	public static function generate(array $dependencies): array
	{
		return [
			'name' => self::$faker->word,
			'surname' => self::$faker->word,
			'phone' => self::$faker->phoneNumber,
			'email' => self::$faker->email,
			'image' => 'default.png',
			'category' => self::$faker->randomElement(['Amis', 'Famille', 'Travail']),
			'favorite' => self::$faker->boolean ? 1 : 0,
			'user_id' => $dependencies['users'][0]['id'],
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		];
	}
}