<?php 

namespace App\Core;
class Database
{
    public \PDO $pdo;

    /**
     * The function is a constructor that initializes a PDO object with the provided database
     * configuration.
     * 
     * @param array config The `$config` parameter is an array that contains the configuration settings
     * for connecting to a MySQL database. It should have the following keys:
     */
    public function __construct(array $config)
    {
        $dsn =  "mysql:host=". $config['host'] ?? ''.";dbname=". $config['name'] ?? '';
        
        $this->pdo = new \PDO($dsn, $config['username'], $config['password']);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec("USE " . $config['name']);
    }

    
}
