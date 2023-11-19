<?php 

namespace Database\Migrations;

use App\Core\Migration;

class m003_create_questions_users_table extends Migration
{
	public string $table = 'questions_users';

	public function __construct(\PDO $pdo)
	{
		parent::__construct($pdo, $this->table);
	}

	public function up()
	{
		parent::up();
		$this->foreignKey('user_id', 'users', 'id');
		$this->varchar('question');
		$this->varchar('reponse');
	}

	public function down()
	{
		parent::down();
	}
}