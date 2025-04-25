<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $postId = (int)$_POST['delete'];
    
    if (PostRepository::deletePost($postId, $_SESSION['pseudo'])) {
        header('Location: /');
        exit();
    } else {
        die("Erreur lors de la suppression ou vous n'êtes pas l'auteur");
    }
}

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


$router = new Router();
$router->start();


