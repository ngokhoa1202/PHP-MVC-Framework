<?php 
namespace app\models;

use app\core\Application;

/**
 * class Model: \app\core\Model
 */

class LoginForm extends \app\core\Model {
  public string $email = '';
  public string $password = '';

  public function rules() : array {
    return [
      "email" => [self::RULE_REQUIRED, self::RULE_EMAIL],
      "password" => [self::RULE_REQUIRED, ]
    ];
  }

  /**
   * find the user based on email, if not found => error
   * check password, if incorrect => error
   * if found => check authenticated
   */
  public function login() {
    $user = (new User())->findOne(['email' => $this->email]);
    
    if (!$user) {
      $this->addError('email', 'The user with this email does not exist');
      return false;
    }

    if (!password_verify($this->password, $user->getPassword())) {
      $this->addError('password', 'The password is incorrect');
      return false;
    }

    Application::$app->login($user);

  }

  public function labels() : array {
    return [
      'email' => 'Email',
      'password' => 'Password'
    ];
  }
}
?>