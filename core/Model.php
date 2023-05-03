<?php 
namespace app\core;

abstract class Model {
  public const RULE_REQUIRED = 'required';
  public const RULE_EMAIL = 'email';
  public const RULE_MIN = 'min';
  public const RULE_MAX = 'max';
  public const RULE_MATCH = 'match'; 
  public const RULE_UNIQUE = 'unique';

  public array $errors = [];

  abstract function rules() : array;

  public function loadData($data) : void {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->{$key} = $value;
      }
    }
  }


  public function validate() {
    foreach ($this->rules() as $attribute => $rules) {
      $value = $this->{$attribute};
      foreach ($rules as $rule) {
        $ruleName = $rule;
        if (!is_string($ruleName)) {
          $ruleName = $rule[0]; //
        }

        if ($ruleName === self::RULE_REQUIRED && !$value) {
          $this->addErrorForRule($attribute, self::RULE_REQUIRED);
        }

        if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
          $this->addErrorForRule($attribute, self::RULE_EMAIL);
        }

        if ($ruleName === self::RULE_MIN && strlen($value) < $rule["min"]) {
          $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
        }

        if ($ruleName === self::RULE_MAX && strlen($value) > $rule["max"]) {
          $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
        }

        if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule["match"]}) { // $this->{$rule}["match] = $this->password
          // $rule['match'] == 'password'
          $rule['match'] = $this->getLabel($rule['match']); // child method => get password label
          $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
        }

        if ($ruleName === self::RULE_UNIQUE) { // email
          $className = $rule['class']; // User
          $uniqueAtrr = $rule['attribute'] ?? $attribute; // email in this case
          $tableName = $className::tableName(); // User
          $statement = DBModel::prepareQuerry("SELECT * FROM $tableName WHERE $uniqueAtrr = :$uniqueAtrr"); // check exists in db
          $statement->bindValue(":$uniqueAtrr", $value);
          $statement->execute();
          $record = $statement->fetchObject();
          if ($record) {
            $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]); // child method
          }
        }
      }
    }

    return empty($this->errors);
  }

  private function addErrorForRule($attribute, $rule, $params = []) : void {
    $errorMess = $this->errorMessages()[$rule] ?? '';

    foreach ($params as $key => $value) { // replace placeholder
      $errorMess = str_replace("{$key}", $value, $errorMess);
    }

    $this->errors[$attribute][] = $errorMess;
  }

  public function addError($attribute, $rule, $params = []) : void {
    $errorMess = $this->errorMessages()[$rule] ?? '';
    $this->errors[$attribute][] = $errorMess;
  }

  public function errorMessages() {
    return [
      self::RULE_REQUIRED =>  "This field is required", // word "field" will be replaced
      self::RULE_EMAIL => "This must be a valid email address",
      self::RULE_MATCH => "This field must be the same as {match}",
      self::RULE_MAX => "This field length must not be greater than {max}",
      self::RULE_MIN => "This field length must not be less than {min}",
      self::RULE_UNIQUE => "This record with {field} already has existed"
    ];
  }

  public function hasError($attribute) {
    return $this->errors[$attribute] ?? false;
  }

  public function getFirstError($attribute) {
    return $this->errors[$attribute][0] ?? false;
  }

  public function getAttribute($attribute) {
    return $this->{$attribute};
  }

  public function setAttribute($attribute, $value) : void {
    $this->{$attribute} = $value;
  }

  public function getLabel($attribute) {
    return $this->labels()[$attribute] ?? false;
  }

  public function labels() : array {
    return [];
  }
}
?>