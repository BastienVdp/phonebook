<?php 

namespace App\Trait;

use App\Core\Model;

trait Relationship 
{

	/**
	 * The function returns the foreign key name for a given model.
	 * 
	 * @param Model model The "model" parameter is an instance of the Model class. It represents the model
	 * for which we want to retrieve the foreign key.
	 * 
	 * @return a string that represents the foreign key for a given model.
	 */
	public function getForeignKey(Model $model)
	{
		$tableName = preg_replace('/s$/', '', $model::getTable());
		$primaryKey = $model->primaryKey();
		return strtolower($tableName . "_$primaryKey");
	}

	/**
	 * This function retrieves all records from a related table based on a foreign key.
	 * 
	 * @param string model The "model" parameter is a string that represents the name of the related model
	 * class. It is used to determine the table name of the related model.
	 * @param string foreignKey The `` parameter is an optional parameter that specifies the
	 * foreign key column name in the related table. If it is not provided, the method will use the
	 * `getForeignKey()` method to determine the foreign key column name based on the current model.
	 * 
	 * @return an array of objects of the specified model type.
	 */
	public function hasMany(string $model, string $foreignKey = null)
	{
		$relatedTableName = $model::getTable();
		$foreignModelKey = !$foreignKey ? $this->getForeignKey($this) : $foreignKey;

		$statement = self::prepare("SELECT * FROM $relatedTableName WHERE $foreignModelKey = :id");
		$statement->bindValue(':id', $this->id);
		$statement->execute();

    	return $statement->fetchAll(\PDO::FETCH_CLASS, $model);
	}

	/**
	 * This function retrieves a related model based on a foreign key.
	 * 
	 * @param string model The "model" parameter is a string that represents the name of the related model
	 * class. It is used to determine the table name and primary key of the related model.
	 * @param foreignKey The  parameter is an optional parameter that allows you to specify a
	 * custom foreign key column name. If you don't provide a value for , the function will use
	 * the default foreign key column name determined by the getForeignKey() method.
	 * 
	 * @return an object of the specified model class.
	 */
	public function belongsTo(string $model, $foreignKey = null)
	{
		$relatedTableName = $model::getTable();
		$instanceModel = new $model;
		$primaryModelKey = $instanceModel->primaryKey();
	
		$foreignModelKey = !$foreignKey ? $this->getForeignKey($instanceModel) : $foreignKey;
			
		$statement = self::prepare("SELECT * FROM $relatedTableName WHERE $primaryModelKey = :foreignkey");
	
		$statement->bindValue(':foreignkey', $this->$foreignModelKey);
		$statement->execute();
	
		return $statement->fetchObject($model);
	}

	/**
	 * This function retrieves the related models in a many-to-many relationship using a pivot table.
	 * 
	 * @param string model The "model" parameter is a string that represents the name of the related model
	 * class. It is used to determine the table name of the related model.
	 * @param string pivotTable The pivotTable parameter is the name of the table that represents the
	 * many-to-many relationship between the current model and the related model. It is the table that
	 * stores the foreign keys of both models.
	 * @param foreignKey The  parameter is an optional parameter that represents the foreign
	 * key column name in the pivot table. If this parameter is not provided, the function will use the
	 * default foreign key column name based on the model name.
	 * 
	 * @return an array of objects of the specified model class.
	 */
	public function belongsToMany(string $model, string $pivotTable, $foreignKey = null)
	{
		$relatedTableName = $model::getTable();
		$pivotForeignKey = $this->getForeignKey($this);
		$relatedForeignKey = $this->getForeignKey(new $model);

		$statement = self::prepare("SELECT $relatedTableName.* FROM $relatedTableName
			JOIN $pivotTable ON $relatedTableName.id = $pivotTable.$relatedForeignKey
			WHERE $pivotTable.$pivotForeignKey = :id");

		$statement->bindValue(':id', $this->id);
		$statement->execute();

		return $statement->fetchAll(\PDO::FETCH_CLASS, $model);
	}

	/**
	 * The function associates a model with another model by inserting records into a pivot table.
	 * 
	 * @param string model The "model" parameter is a string that represents the name of the related model
	 * that you want to associate with the current model. It is used to determine the name of the pivot
	 * table that will be used to store the association.
	 * @param array data An array of related model data. Each element in the array should be an
	 * associative array representing a related model, with the 'id' key containing the ID of the related
	 * model.
	 * 
	 * @return array|static an array or an instance of the class that the function belongs to.
	 */
	public function associate(string $model, array $data): array|static
    {
        $pivotTable = str_replace('s', '', $this->getTable()) . "_" . str_replace('s', '', $model::getTable());
        $pivotForeignKey = $this->getForeignKey($this);
        $relatedForeignKey = $this->getForeignKey(new $model);

		foreach($data as $key => $value) {
			$statement = self::prepare("INSERT INTO $pivotTable ($pivotForeignKey, $relatedForeignKey) VALUES (:id, :relatedId)");

			$statement->bindValue(':id', $this->id);
			$statement->bindValue(':relatedId', $value['id']);

			$statement->execute();
		}

        return $this->find(['id' => $this->id]);
    }
}