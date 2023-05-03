<?php 
namespace app\controllers;

use app\core\Application;
use app\models\User;

use app\core\Request;

use app\core\Controller;
use app\core\Response;
use app\models\LoginForm;

class AuthController extends Controller {
  public function login(Request $request) {
    $loginForm = new LoginForm();
    if ($request->isPostMethod()) {
      $loginForm->loadData($request->getBody());
      if ($loginForm->validate() && $loginForm->login()) {
        Application::$app->response->redirect("/");
        return;
      }
    }
    $this->setLayout('auth');
    return $this->render("login", [
      "model" => $loginForm
    ]); // ~ $model = Login object (variable variable: takes content as a new variable name)
  }

  /**
   * @param \app\models\User $registerModel earlier
   */
  public function register(Request $request) {
    $user = new User();
    if ($request->isPostMethod()) {
      $user->loadData($request->getBody()); 

      if ($user->validate() && $user->save()) { // define in Model
        Application::$app->session->setFlash("success", "Thank you for your registering");
        Application::$app->response->redirect("/");
      }

      return $this->render("register", [
        "model" => $user
      ]); // render register form again
    }
    $this->setLayout('auth');

    return $this->render("register", [
        "model" => $user
    ]); // $model = User object
  } 


  public function logout(Request $request) {
    Application::$app->logout();
    Application::$app->response->redirect("/");
  }
};
?>