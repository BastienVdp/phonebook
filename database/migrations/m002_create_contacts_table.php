<?php 

namespace Database\Migrations;

use App\Core\Migration;

class m002_create_contacts_table extends Migration
{
	public string $table = 'contacts';

	public function __construct(\PDO $pdo)
	{
		parent::__construct($pdo, $this->table);
	}

	public function up()
	{
		parent::up();
		$this->varchar('name');
		$this->varchar('surname');
		$this->varchar('email');
		$this->varchar('phone');
		$this->varchar('image', default: 'default.png');
		$this->varchar('category');
		$this->bool('favorite', true, 0);
		$this->foreignKey('user_id', 'users', 'id');
		$this->addTimestamps();
	}

	public function down()
	{
		parent::down();
	}
}