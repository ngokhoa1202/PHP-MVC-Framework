<?php 
namespace app\core;
use app\core\Application;

class Controller {
  protected string $layout = 'main';
  public function render($view, $params = []) {
    return Application::$app->router->renderView($view, $params);
  }

  public function setLayout($layout) : void {
    $this->layout = $layout;
  }

  public function getLayout() : string {
    return $this->layout;
  }

};
?>