<?php 

namespace Database\Seeders;

use App\Core\Factory;
use Database\Factories\UserFactory;
use Database\Factories\ContactFactory;
use Database\Factories\ProductFactory;
use Database\Factories\CategoryFactory;

class DatabaseSeeder 
{
	public function __construct(\PDO $pdo)
	{
		Factory::init($pdo);

		$this->runSeeders();
	}

	private function runSeeders()
	{
		$users = UserFactory::create(1);
		ContactFactory::create(10, ['users' => $users]);
	}
}