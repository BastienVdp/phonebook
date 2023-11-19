<?php 

namespace Database\Migrations;

use App\Core\Migration;

class m001_create_users_table extends Migration
{
	public string $table = 'users';

	public function __construct(\PDO $pdo)
	{
		parent::__construct($pdo, $this->table);
	}

	public function up()
	{
		parent::up();
		$this->varchar('name');
		$this->varchar('surname');
		$this->varchar('username');
		$this->varchar('email');
		$this->varchar('password');
		$this->bool('admin', default: 0);
		$this->varchar('reset_token', nullable: true);
		$this->addTimestamps();
	}

	public function down()
	{
		parent::down();
	}
}