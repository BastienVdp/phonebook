<?php 

namespace App\Core;

use Faker\Factory as FakerFactory;

/* 
 * The Factory class is used to create and save instances
 * of a specified class into a database table. 
 */
class Factory
{
	public static \PDO $pdo;
	public static $faker;

	/**
	 * The function initializes the PDO object and creates a FakerFactory object.
	 * 
	 * @param \PDO pdo The  parameter is an instance of the PDO class, which is used for connecting to
	 * and interacting with a database. It is typically used for executing SQL queries and managing
	 * database transactions.
	 */
	public static function init(\PDO $pdo): void
	{
		self::$pdo = $pdo;
		self::$faker = FakerFactory::create();
	}

	/**
	 * The function creates a specified number of instances of a class and saves them.
	 * 
	 * @param int count The "count" parameter specifies the number of instances to create. By default, it
	 * is set to 1, meaning that if no value is provided, only one instance will be created.
	 * @param array dependencies The "dependencies" parameter is an array that allows you to pass any
	 * additional dependencies or arguments that are required for generating the factories. These
	 * dependencies can be used within the "generate" method to customize the creation of each factory.
	 * 
	 * @return the result of the `save` method, which is called with the `` array as an
	 * argument.
	 */
	public static function create(int $count = 1, array $dependencies = [])
	{
		$factories = [];

		for($i = 0; $i < $count; $i++) {
			$factories[] = static::class::generate($dependencies);
		}
	
		return self::save($factories);
	}

	/**
	 * The function saves an array of factories into a database table and returns all the records.
	 * 
	 * @param factories An array of arrays, where each inner array represents a set of values to be
	 * inserted into the database table. Each inner array should have keys that correspond to the column
	 * names in the table, and values that represent the values to be inserted.
	 * 
	 * @return the result of the `fetchAll()` method.
	 */
	public static function save($factories): array
	{
		$table = static::class::$model::getTable();
		$columns = implode(', ', array_keys($factories[0]));
		$values = implode(', ', array_map(function($factory) {
			return '('. implode(', ', array_map(function($value) {
				return "'$value'";
			}, $factory)) .')';
		}, $factories));

		$sql = "INSERT INTO $table ($columns) VALUES $values";

		$statement = self::$pdo->prepare($sql);
		$statement->execute();
		
		return self::getRecords();
	}

	public static function getRecords(): array
	{
		$table = static::class::$model::getTable();
		$sql = "SELECT * FROM $table";

		$statement = self::$pdo->prepare($sql);
		$statement->execute();

		return $statement->fetchAll();
	}
}