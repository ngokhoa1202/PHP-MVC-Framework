<?php 

use app\core\Application;
use app\models\User;

require_once __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
  "userClass" => User::class,
  "db" => [
    "dsn" => $_ENV["DB_DSN"],
    "username" => $_ENV["DB_USER"],
    "password" => $_ENV["DB_PASSWORD"],
  ]
  ]; // .env file

error_reporting(E_ALL & ~E_DEPRECATED);
//                      ^ this on
// trigger_error('Deprecated', E_USER_DEPRECATED);

$app = new Application(dirname(__DIR__), $config);

$app->router->get("/", [\app\controllers\SiteController::class, "home"]);

$app->router->get("/contact", [\app\controllers\SiteController::class, "contact"]);


$app->router->post("/contact", [\app\controllers\SiteController::class, "handleContact"]);

$app->router->get("/login", [\app\controllers\AuthController::class, "login"]);
$app->router->post("/login", [\app\controllers\AuthController::class, "login"]);
$app->router->get("/register", [\app\controllers\AuthController::class, "register"]);
$app->router->get("/logout", [\app\controllers\AuthController::class, "logout"]);

$app->run();
?>

