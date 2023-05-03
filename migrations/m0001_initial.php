<?php 
class m0001_initial {
  public function up() {
    echo "Applying migrations".PHP_EOL;
    $db = \app\core\Application::$app->database;
    $SQL = "
      CREATE TABLE User(
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        firstname VARCHAR(255) NOT NULL,
        secondName VARCHAR(255) NOT NULL, 
        status TINYINT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      );
    ";
    $db->pdo->exec($SQL);
  }

  public function down() {
    echo "Down migrations".PHP_EOL;
    echo "Applying migrations".PHP_EOL;
    $db = \app\core\Application::$app->database;
    $SQL = "
      DROP TABLE User;
    ";
    $db->pdo->exec($SQL);
  }
}
?>