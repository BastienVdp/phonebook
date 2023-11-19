<?php

namespace App\Core;

use Database\Seeders\DatabaseSeeder;

class Runner
{
    private $pdo;

    public function __construct($config)
    {
        $this->pdo = new \PDO("mysql:host=" . $config['database']['host'] . ";dbname=" . $config['database']['name'], $config['database']['username'], $config['database']['password']);
    }

    /**
     * The function applies seeders to the database if they exist, otherwise it logs that no seeders
     * were found.
     * 
     * @return void void, which means it is not returning any value.
     */
    public function applySeeders(): void
    {
        $this->log("Applying seeders");

        if (!$this->seedersExist()) {
            $this->log("No seeders found");
            return;
        }

        $this->runDatabaseSeeder();

        $this->log("Seeders applied");
    }

    
    /**
     * The function applies migrations to the database, optionally refreshing it before applying new
     * migrations.
     * 
     * @param bool refresh The "refresh" parameter is a boolean flag that determines whether to refresh
     * the migrations. If set to true, it will drop all existing migrations and recreate them. If set
     * to false, it will only create new migrations without dropping the existing ones.
     * 
     * @return bool a boolean value of true.
     */
    public function applyMigrations(bool $refresh = false): bool
    {
        if ($refresh) {
            $this->dropMigrations();
        }

        $this->createMigrations();
        $newMigrations = $this->applyNewMigrations($refresh);

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
            $this->log("Saved migrations to database");
        } else {
            $this->log("All migrations are applied");
        }

        return true;
    }

    /**
     * The function checks if there are any seeders (database factories) in the specified directory.
     * 
     * @return bool a boolean value.
     */
    private function seedersExist(): bool
    {
        return !empty(scandir(dirname(__DIR__) . '/../database/factories'));
    }

    private function runDatabaseSeeder(): void
    {
        new DatabaseSeeder($this->pdo);
    }

    /**
     * The function `applyNewMigrations` applies new migrations to the database and returns an array of
     * the applied migrations.
     * 
     * @param bool refresh A boolean value indicating whether to refresh the migrations. If set to
     * true, the down method of each migration will be called before applying the up method.
     * 
     * @return array an array of new migrations that have been applied.
     */
    private function applyNewMigrations(bool $refresh): array
    {
        $migrations = $this->getMigrations();
        $newMigrations = [];

        $files = scandir(dirname(__DIR__) . '/../database/migrations');
        $toApplyMigrations = array_diff($files, $migrations);

        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            $className = pathinfo($migration, PATHINFO_FILENAME);
            $cn = 'Database\Migrations\\' . $className;
            $instance = new $cn($this->pdo);

            $this->log("Applying migration $migration");

            if ($refresh) {
                $instance->down();
            }

            $instance->up();

            $this->log("Applied migration $migration");

            $newMigrations[] = $migration;
        }

        return $newMigrations;
    }

    private function createMigrations(): void
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;");
    }

    private function dropMigrations(): void
    {
        $this->pdo->exec("DROP TABLE IF EXISTS migrations");
    }

    private function getMigrations(): array
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function saveMigrations(array $migrations): void
    {
        $placeholders = implode(", ", array_fill(0, count($migrations), "(?)"));
        $query = "INSERT INTO migrations (migration) VALUES $placeholders";

        $statement = $this->pdo->prepare($query);
        $statement->execute($migrations);
    }

    private function log(string $message): void
    {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
        echo '-----------------------------------------' . PHP_EOL;
    }
}
