<?php

class LoginController extends Controller
{

    public function index()
    {
        $errors = [];

        if (isset($_POST['pseudo'])) {

            $user = new User($_POST['pseudo'], $_POST['password'], null);

            $hash = UserRepository::checkPassword($user);
            if ($hash && password_verify($user->getPassword(), $hash)) {
                $_SESSION['pseudo'] = $_POST['pseudo'];
                $_SESSION['password'] = $_POST['password'];
                header('Location: http://localhost:8000/post');
                exit;
            } else {
                echo "Pseudo ou mot de passe incorrect.";
            }
        }
        require_once("../views/login.php");
    }
}
