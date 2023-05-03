<?php 
namespace app\models;

use app\core\UserModel;


class User extends UserModel {
  const STATUS_INACTIVE = 0;
  const STATUS_ACTIVE = 1;
  const STATUS_DELETED = 2;

  protected string $firstName = '';
  protected string $secondName = '';
  protected string $email = '';
  protected string $password = '';
  protected string $confirmingPassword = '';
  protected int $status = self::STATUS_INACTIVE;

  public function getFirstName(): string {
    return $this->firstName;
  }

  public function setFirstName(string $firstName): void {
    $this->firstName = $firstName;
  }

  public function getSecondName(): string {
    return $this->secondName;
  }

  public function setSecondName($secondName): void {
    $this->secondName = $secondName;
  }

  public function getPassword(): string {
    return $this->password;
  }

  public function setPassword(string $password): void {
    $this->password = $password;
  }

  public function getConfirmingPassword(): string {
    return $this->confirmingPassword;
  }

  public function setConfirmingPassword(string $confirmingPassword): void {
    $this->confirmingPassword = $confirmingPassword;
  }

  public function getStatus(): int {
    return $this->status;
  }

  public function setStatus(int $status): void {
    $this->status = $status;
  }

  public function save() {
    // encrypt password
    $this->status = self::STATUS_INACTIVE;
    $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    return parent::save();
  }

  public function rules() : array{
    return [
      "firstName" => [self::RULE_REQUIRED],
      "secondName" => [self::RULE_REQUIRED],
      "email" => [self::RULE_REQUIRED, self::RULE_EMAIL,  [
        self::RULE_UNIQUE, 'class' => self::class
      ]],
      "password" => [self::RULE_REQUIRED, [self::RULE_MIN, "min" => 8], [self::RULE_MAX, "max" => 50]],
      "confirmingPassword" => [self::RULE_REQUIRED, [self::RULE_MATCH, "match" => "password"]],
    ];
  }

  public function attributes(): array {
    return ["firstName", "secondName", "email", "password", "status"];
  }

  public function tableName(): string {
    return "User";
  }

  /**
   * @override: Model::labels
   */

  public function labels() : array {
    return [
      'firstName' => 'First Name',
      'secondName' => 'Second Name',
      'email' => 'Email',
      'password' => 'Password',
      'confirmingPassword' => 'Confirm your password'
    ];
  }

  public function primaryKey(): string {
    return 'id';
  }

  public function getDisplayName(): string {
    return $this->firstName . ' ' . $this->secondName;
  }

};
?>