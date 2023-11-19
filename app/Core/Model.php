<?php 

namespace App\Core;
abstract class Model
{
    abstract static function getTable(): string;
    abstract static function getAttributes(): array;

    public function primaryKey(): string
    {
        return 'id';
    }

    public static function prepare(string $sql): \PDOStatement
    {
        return Application::$app->database->pdo->prepare($sql);
    }

   /**
    * The function retrieves all records from a database table and returns them as an array, with an
    * optional filter and order.
    * 
    * @param string filter The "filter" parameter is used to specify the column by which the results
    * should be filtered. It is a string that represents the column name in the database table.
    * @param string order The "order" parameter is used to specify the order in which the results
    * should be sorted. It accepts two values: "ASC" for ascending order and "DESC" for descending
    * order. By default, if an invalid value is provided, it will be set to "ASC".
    * 
    * @return array an array of objects of the class that called the function.
    */
    public static function all(string $filter = 'id', string $order = 'ASC'): array
    {
        $order = strtoupper($order);
        if($order !== 'ASC' && $order !== 'DESC') $order = 'ASC';
        
        $statement = self::prepare("SELECT * FROM " . static::getTable() . " ORDER BY " . $filter . " " . $order);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, static::class);
    }

   /**
    * The `find` function takes an array of conditions, generates a SQL query based on those
    * conditions, executes the query, and returns the result as an array of objects or a single object.
    * 
    * @param array where The `where` parameter is an associative array that specifies the conditions
    * for the SQL query. The keys of the array represent the column names, and the values represent the
    * desired values for those columns.
    * 
    * @return array|static an array or an instance of the class that called the function.
    */
    public static function find(array $where, string $orderBy = ''): array|static
    {
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", array_keys($where)));
    
        $orderByClause = $orderBy ? " ORDER BY $orderBy" : "";
        $statement = self::prepare("SELECT * FROM " . static::getTable() . " WHERE " . $sql . " " . $orderByClause);
        
        foreach ($where as $key => $value) {
            $statement->bindValue(":$key", $value);
        }
    
        $statement->execute();
    
        if ($statement->rowCount() > 1) {
            return $statement->fetchAll(\PDO::FETCH_CLASS, static::class);
        } elseif ($statement->rowCount() == 1) {
            return $statement->fetchObject(static::class);
        } else {
            return [];
        }
    }

    /**
     * The function getLastInsertId() returns the last inserted ID from the database.
     * 
     * @return int the last inserted ID from the database as an integer.
     */
    public static function getLastInsertId(): int
    {
        return Application::$app->database->pdo->lastInsertId();
    }

   /**
    * The function creates a new record in the database table using the provided data and returns the
    * created record if successful, otherwise returns null.
    * 
    * @param array data An array containing the data to be inserted into the database table. The keys
    * of the array represent the column names, and the values represent the corresponding values to be
    * inserted.
    * 
    * @return null|static either an instance of the class (static) or null.
    */
    public static function create(array $data): null|static
    {
        $params = array_map(fn($attr) => ":$attr", static::getAttributes());

        $statement = self::prepare(
            "
			INSERT INTO " . static::getTable() . " 
			(" . implode(',', static::getAttributes()) . ") 
			VALUES (" . implode(',', $params) . ")
		"
        );

        foreach(static::getAttributes() as $attribute) {
            $statement->bindValue(":$attribute", $data[$attribute]);
        }

        $statement->execute();

        if (self::getLastInsertId()) {
            return self::find(['id' => self::getLastInsertId()]);
        }

        return null;
    }

    /**
     * The function updates a record in a database table based on the given conditions and returns the
     * updated record.
     * 
     * @param array where An associative array representing the conditions for the update query. The
     * keys of the array are the column names and the values are the corresponding values to match.
     * @param array data An associative array containing the data to be updated in the database. The
     * keys of the array represent the column names, and the values represent the new values for those
     * columns.
     * 
     * @return array|static the result of the `find` method, which is either an array or an instance of
     * the class that the `update` method is defined in (`static`).
     */
    public static function update(array $where, array $data): array|static
    {
        $sql = implode(",", array_map(fn($attr) => "$attr = :$attr", array_keys($data)));
        $whereSql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", array_keys($where)));
        
        $statement = self::prepare("UPDATE " . static::getTable() . " SET " . $sql . " WHERE " . $whereSql);
        
        foreach([...$where, ...$data] as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        $statement->execute();

        return self::find($where);
    }

   /**
    * The function deletes rows from a database table based on the given conditions.
    * 
    * @param array where The `` parameter is an associative array that specifies the conditions
    * for deleting rows from the table. The keys of the array represent the column names, and the
    * values represent the values to match for deletion.
    * 
    * @return bool a boolean value, specifically `true`.
    */
    public static function delete(array $where): bool
    {
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", array_keys($where)));
        
        $statement = self::prepare("DELETE FROM " . static::getTable() . " WHERE " . $sql);
        
        foreach($where as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        $statement->execute();

        return true;
    }
}
