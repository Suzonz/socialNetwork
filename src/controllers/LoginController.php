<?php

class LoginController extends Controller
{

    public function index()
    {
        $errors = [];

        if (isset($_POST['pseudo'])) {

            $user = new User($_POST['pseudo'], $_POST['password'] ?? '', null);

            $found = UserRepository::findForLogin($user);
            if ($found && password_verify($user->getPassword(), $found['password'])) {
                // Nouvel identifiant de session à la connexion
                session_regenerate_id(true);
                // Le pseudo tel qu'il est enregistré (le mot de passe n'est jamais gardé en session)
                $_SESSION['pseudo'] = $found['pseudo'];
                header('Location: /');
                exit;
            } else {
                $errors[] = "Pseudo ou mot de passe incorrect.";
            }
        }
        require_once("../views/login.php");
    }
}
