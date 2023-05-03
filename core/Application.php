<?php 
namespace app\core;
/**
 * @package: app\core
 */
class Application {
  public static string $ROOT_DIR;
  public Router $router;
  public Request $request;
  public Response $response;
  public Session $session;
  public static Application $app;
  public Database $database;
  public Controller $controller;
  
  public function __construct($rootPath, array $config) {
    self::$ROOT_DIR = $rootPath;
    $this->request = new Request();
    $this->response = new Response();
    $this->router = new Router($this->request, $this->response);
    $this->session = new Session();
    $this->database = new Database($config["db"]);
    self::$app = $this;
  }

  public function get() {
    //to do
  }

  public function run() {
    $this->router->resolve();
  }

  public function getController() : Controller {
    return $this->controller;
  }

  public function setController($controller) : void {
    $this->controller = $controller;
  }
};
?>