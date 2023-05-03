<?php 

use app\core\Application;
require_once __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
  "db" => [
    "dsn" => $_ENV["DB_DSN"],
    "username" => $_ENV["DB_USER"],
    "password" => $_ENV["DB_PASSWORD"],
  ]
  ]; // .env file

$app = new Application(dirname(__DIR__), $config);

$app->router->get("/", [\app\controllers\SiteController::class, "home"]);

$app->router->get("/contact", [\app\controllers\SiteController::class, "contact"]);


$app->router->post("/contact", [\app\controllers\SiteController::class, "handleContact"]);

$app->router->get("/login", [\app\controllers\AuthController::class, "login"]);
$app->router->post("/login", [\app\controllers\AuthController::class, "login"]);
$app->router->get("/register", [\app\controllers\AuthController::class, "register"]);
$app->router->post("/register", [\app\controllers\AuthController::class, "register"]);

$app->run();
?>

