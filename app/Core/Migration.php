<?php 

namespace App\Core;
class Migration
{
	protected \PDO $pdo;
	public string $table;

	public function __construct(\PDO $pdo = null, string $table)
	{
		$this->pdo = $pdo;
		$this->table = $table;
	}

	/**
	 * The function creates a table in the database.
	 */
	public function up()
	{
		$this->createTable($this->table);
	}

	/**
	 * The function disables foreign key checks, drops a table, and then enables foreign key checks again.
	 */
	public function down()
	{
		$this->pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
		$this->dropTable($this->table);
		$this->pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
	}

	private function addColumn(string $column, string $type)
	{
		$sql = "ALTER TABLE $this->table ADD COLUMN $column $type";

		$this->pdo->exec($sql);
	}

	public function varchar(string $column, int $length = 255, bool $nullable = false, string $default = '0.0')
	{
		if ($nullable) {
			$this->addColumn($column, "VARCHAR($length) DEFAULT $default");
			return;
		}
		$this->addColumn($column, "VARCHAR($length) NOT NULL");
	}

	public function mediumText(string $column, bool $nullable = false, string $default = '0.0')
	{
		if ($nullable) {
			$this->addColumn($column, "MEDIUMTEXT DEFAULT $default");
			return;
		}
		$this->addColumn($column, "MEDIUMTEXT NOT NULL");
	}

	public function float(string $column, bool $nullable = false, string $default = '0.0')
	{
		if ($nullable) {
			$this->addColumn($column, "FLOAT DEFAULT $default");
			return;
		}
		$this->addColumn($column, "FLOAT NOT NULL");
	}

	public function integer(string $column, bool $nullable = false, string $default = '0')
	{
		if ($nullable) {
			$this->addColumn($column, "INT DEFAULT $default");
			return;
		}
		$this->addColumn($column, "INT NOT NULL");
	}

	public function bool(string $column, bool $nullable = false, string $default = '0')
	{
		if ($nullable) {
			$this->addColumn($column, "BOOLEAN DEFAULT $default");
			return;
		}
		$this->addColumn($column, "BOOLEAN NOT NULL DEFAULT $default");
	}

	public function foreignKey(string $column, string $referenceTable, string $referenceColumn, string $onDelete = 'CASCADE')
	{
		$this->addColumn($column, "INT NOT NULL");
		$this->addForeignKey($column, $referenceTable, $referenceColumn, $onDelete);
	}

	public function dropColumn(string $column)
	{
		$sql = "ALTER TABLE $this->table DROP COLUMN $column";

		$this->pdo->exec($sql);
	}

	public function updateColumn(string $column, string $type)
	{
		$sql = "ALTER TABLE $this->table MODIFY COLUMN $column $type";

		$this->pdo->exec($sql);
	}


	public function addForeignKey(string $column, string $referenceTable, string $referenceColumn, string $onDelete = 'CASCADE')
	{
		$sql = "ALTER TABLE $this->table ADD FOREIGN KEY ($column) REFERENCES $referenceTable($referenceColumn ) ON DELETE $onDelete";

		$this->pdo->exec($sql);
	}

	public function dropForeignKey(string $constraintName)
	{
		$sql = "ALTER TABLE $this->table DROP FOREIGN KEY $constraintName";
		$this->pdo->exec($sql);
	}

	public function addPrimaryKey(string $column)
	{
		$sql = "ALTER TABLE $this->table ADD COLUMN $column PRIMARY KEY ($column)";

		$this->pdo->exec($sql);
	}

	public function dropPrimaryKey(string $column)
	{
		$sql = "ALTER TABLE $this->table DROP PRIMARY KEY ($column)";

		$this->pdo->exec($sql);
	}

	public function addTimestamps()
	{
		$this->addColumn('created_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
		$this->addColumn('updated_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
	}

	/**
	 * The function creates a table in the database if it doesn't already exist, with an auto-increment
	 * primary key.
	 * 
	 * @param string table The name of the table that needs to be created in the database.
	 */
	private function createTable(string $table)
	{
		$this->dropTable($table);
		$instanceModel = "App\\Models\\" . ucfirst(substr($table, 0, -1));
		
		$primaryKey = class_exists($instanceModel) ? (new $instanceModel())->primaryKey() : 'id';
		$sql = "CREATE TABLE IF NOT EXISTS $table ( `$primaryKey` INT AUTO_INCREMENT PRIMARY KEY) ENGINE=INNODB DEFAULT CHARSET=utf8mb4;";

		$this->pdo->exec($sql);
	}

	private function dropTable(string $table)
	{
		$sql = "DROP TABLE IF EXISTS $table";

		$this->pdo->exec($sql);
	}
}