<?php 
namespace app\core;
use app\core\Application;
use app\core\middlewares\BaseMiddleware;

class Controller {
  protected string $layout = 'main';
  public string $action = '';

  /**
   * @var \app\core\middlewares\BaseMiddleware[]
   */
  protected array $middlewares = []; 

  public function render($view, $params = []) {
    return Application::$app->router->renderView($view, $params);
  }

  public function setLayout($layout) : void {
    $this->layout = $layout;
  }

  public function getLayout() : string {
    return $this->layout;
  }

  /**
   * add middleware to middlewares array
   */

  public function registerMiddleware(BaseMiddleware $middleware) {
    $this->middlewares[] = $middleware;
  }

  public function getMiddlewares() : array {
    return $this->middlewares;
  }
};
?>