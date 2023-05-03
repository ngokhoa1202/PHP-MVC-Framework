<?php 
class m0002_addPasswordColumn {
  public function up() {
    echo "Applying migrations".PHP_EOL;
    $db = \app\core\Application::$app->database;
    $SQL = "
      ALTER TABLE User
        ADD COLUMN password VARCHAR(512) NOT NULL;
    ";
    $db->pdo->exec($SQL);
  }

  public function down() {
    echo "Down migrations".PHP_EOL;
    echo "Applying migrations".PHP_EOL;
    $db = \app\core\Application::$app->database;
    $SQL = "
      ALTER TABLE User
        DROP COLUMN password;
    ";
    $db->pdo->exec($SQL);
  }
}
?>