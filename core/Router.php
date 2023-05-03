<?php 
namespace app\core;
/**
 * @package: app\core
 */
class Router {
  protected array $routes = [];
  public Request $request;
  public Response $response;

  public function __construct(Request $request, Response $response) {
    $this->request = $request;
    $this->response = $response;
  }

  public function get($path, $callback) : void {
    $this->routes['get'][$path] = $callback;
  }

  public function renderView($view, $params = []) : void {
    $layoutContent = $this->layoutContent();
    $viewContent = $this->renderOnlyView($view, $params);
    echo str_replace("{{content}}", $viewContent, $layoutContent);
  }

  public function renderContent($viewContent) : void {
    $layoutContent = $this->layoutContent();
    echo str_replace("{{content}}", $viewContent, $layoutContent);
  }

  public function resolve() {
    $path = $this->request->getPath();
    $method = $this->request->getMethod();
    $callback = $this->routes[$method][$path] ?? false;

    if ($callback === false) {
      $this->response->setStatusCode(404);
      return $this->renderView("_404");
    }

    if (is_string($callback)) {
      return $this->renderView($callback);
    }

    if (is_array($callback)) { // PHP version >= 8.0, create an instance of controller
      // at first $callback is an associative array
      // array(2) {
      //   [0]=>
      //   string(30) "app\controllers\SiteController"
      //   [1]=>
      //   string(4) "home"
      // }
      Application::$app->controller = new $callback[0](); // PHP version >= 8.0, create an instance of controller
      $callback[0] = Application::$app->controller;
    }

    return call_user_func($callback, $this->request);
  }

  protected function layoutContent() {
    $layout = Application::$app->controller->getLayout();
    ob_start();
    include_once Application::$ROOT_DIR."/views/layouts/$layout.php";
    return ob_get_clean();
  }

  protected function renderOnlyView($view, $params = []) {
    foreach ($params as $key => $value) {
      $$key = $value;
    } // $model = User object
    ob_start();
    include_once Application::$ROOT_DIR."/views/$view.php";
    return ob_get_clean();
  }

  public function post($path, $callback) {
    $this->routes['post'][$path] = $callback;
  }

};
?>