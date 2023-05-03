<?php 
namespace app\core;

abstract class DBModel extends Model {
  abstract public function tableName() : string;
  abstract public function attributes() : array;
  
  public function save() {
    $tableName = $this->tableName();
    $attributes = $this->attributes();
    $params = array_map(fn($attribute) => ":$attribute" , $attributes);
    $statement = self::prepareQuerry("
      INSERT INTO $tableName(".implode(',', $attributes).") 
        VALUES(".implode(',', $params)."); 
    ");

    // binding attributes
    foreach ($attributes as $attribute) {
      $statement->bindValue(":$attribute", $this->{$attribute});
    }
    $statement->execute();
    return true;
  }

  public static function prepareQuerry($sql) {
    return Application::$app->database->pdo->prepare($sql);
  }

  /**
   * @param where ['email' => 'abc@gmail.com', 'firstName' => 'Khoa']
   */

  public static function findOne($where) {
    $tableName = static::tableName();
    $attributes = array_keys($where);
    
    // SELECT * FROM User WHERE email = ... AND firstName = ...
    $sql = implode('AND: ', array_map(fn($attribute) => "$attribute = :$attribute", attributes));
    $statement = self::prepareQuerry("SELECT * from $tableName WHERE $sql;");

    foreach ($where as $key => $value) {
      $statement->bindValue(":$key", $value);
    }

    $statement->execute();
    return $statement->fetchObject(static::class); // User
  }
}
?>