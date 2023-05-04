<?php 
namespace app\core;

use \app\core\models\User;
use Exception;

/**
 * @package: app\core
 */
class Application {
  public static string $ROOT_DIR;
  public string $userClass;
  public Router $router;
  public Request $request;
  public Response $response;
  public Session $session;
  public static Application $app;
  public Database $database;
  public ?DBModel $user; // maybe a guest => null
  public ?Controller $controller = null;
  public string $layout = 'main';
  
  public function __construct($rootPath, array $config) {
    self::$ROOT_DIR = $rootPath;
    $this->request = new Request();
    $this->response = new Response();
    $this->router = new Router($this->request, $this->response);
    $this->session = new Session();
    $this->database = new Database($config["db"]);
    $this->userClass = $config['userClass'];
    self::$app = $this;

    // reload page => keep record of one user 
    $primaryValue = $this->session->get('user');

    if ($primaryValue) {
      $primaryKey = (new $this->userClass())->primaryKey();
    
      $this->user = (new $this->userClass())->findOne([
        $primaryKey => $primaryValue
      ]); 
    }
    else {
      $this->user = null;
    }
    
  }

  public function get() {
    //to do
  }

  public function run() {
    try {
      $this->router->resolve();
    }
    catch (Exception $exception) {
      $this->router->renderView('_error', [
        'exception' => $exception
      ]);
    }
    
  }

  public function getController() : Controller {
    return $this->controller;
  }

  public function setController($controller) : void {
    $this->controller = $controller;
  }

  /**
   * @param $user: actually a child class of DBModel abstract class
   * @return: set session[key] = value of logined user.
   */
  public function login(DBModel $user) {
    $this->user = $user;
    $primaryKey = $user->primaryKey(); // 'id'

    $primaryValue = $user->{$primaryKey}; // user.id = 1
    $this->session->set('user', $primaryValue);
  }

  public function logout() {
    $this->user = null;
    $this->session->remove('user');
  }

  public static function isGuest() {
    return !Application::$app->user;
  }
};
?>