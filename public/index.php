<?php
// En local, les erreurs sont affichées ; en ligne, mettre APP_DEBUG=0 chez l'hébergeur
define('APP_DEBUG', getenv('APP_DEBUG') !== '0');
error_reporting(E_ALL);
ini_set('display_errors', APP_DEBUG ? '1' : '0');

// Cookie de session : invisible pour JavaScript, non envoyé depuis un autre site
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => !empty($_SERVER['HTTPS'])]);
session_start();

//db et Router
require_once('../core/Router.php');
require_once("../src/models/Db.php");

//les repositories
require_once("../src/models/repositories/UserRepository.php");
require_once("../src/models/repositories/PostRepository.php");

//modeles
require_once("../src/models/User.php");
require_once("../src/models/Post.php");

//le controller abstract
require_once("../src/controllers/Controller.php");

//les autres controlleurs
require_once("../src/controllers/MainController.php");
require_once("../src/controllers/RegisterController.php");
require_once("../src/controllers/LoginController.php");
require_once("../src/controllers/LogoutController.php");
require_once("../src/controllers/PostController.php");
require_once("../src/controllers/EditController.php");
require_once("../src/controllers/DeleteController.php");


$router = new Router();
$router->start();


