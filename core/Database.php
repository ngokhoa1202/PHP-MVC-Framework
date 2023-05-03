<?php 

namespace app\core;

class Database {
  public \PDO $pdo;

  public function __construct(array $config) {
    $dsn = $config["dsn"] ?? '';
    $username = $config["username"] ?? '';
    $password = $config["password"] ?? '';
    $this->pdo = new \PDO($dsn, $username, $password);
    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); //throw exception if connection fails
  }

  public function applyMigrations() {
    $this->createMigrationsTable();
    $appliedMigrations = $this->getAppliedMigrations();
    $files = scandir(Application::$ROOT_DIR."/migrations");
    $toApplyMigrations =  array_diff($files, $appliedMigrations);

    foreach ($toApplyMigrations as $migration) {
      if ($migration === '.' || $migration === '..') {
        continue;
      }

      require_once Application::$ROOT_DIR."/migrations/".$migration;
      $className = pathinfo($migration, PATHINFO_FILENAME);
      $instance = new $className(); // migrations instance. Example: m0001_initial
      echo $this->log("Applying migration $migration".PHP_EOL);
      $instance->up();
      //$instance->down();
      echo $this->log("Applied migration $migration".PHP_EOL);

      $newMigration[] = $migration;
    }
    if (!empty($newMigration)) {
      $this->saveMigrations($newMigration);
    }
    else {
      return $this->log("All migrations has been applied");
    }
  }

  public function createMigrationsTable() {
    $this->pdo->exec("
      CREATE TABLE IF NOT EXISTS Migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255), 
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      );
    ");
  }

  public function getAppliedMigrations() {
    $statement = $this->pdo->prepare("SELECT migration FROM migrations");
    $statement->execute();
    return $statement->fetchAll(\PDO::FETCH_COLUMN); 
  }

  public function saveMigrations(array $migrations) {
    // concatenation all migration to insert into db
    $str = implode(",", $migrations = array_map(fn($migration) => "('$migration')", $migrations)); // fn: array function
    $statement = $this->pdo->prepare("
      INSERT INTO migrations(migration) VALUES
        $str;
    ");
    $statement->execute();
  }

  protected function log($message) {
    echo '[' .date('y-m-d h:i:s') . '] - ' . $message . PHP_EOL;
  }
}
?>