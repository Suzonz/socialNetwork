<?php

class RegisterController extends Controller
{
    public function index()
    {
        $errors = [];

        try {
            if (isset($_POST['pseudo'])) {
                $pseudo = $_POST['pseudo'];
                $password = $_POST['password'];
                $email = $_POST['email'];

                // Vérifier les champs vides
                if (empty($pseudo) || empty($password) || empty($email)) {
                    $errors[] = "Tous les champs sont requis.";
                }

                // Validations
                if (!preg_match("/^[a-zA-Z]{1,20}[0-9]{0,3}$/", $pseudo)) {
                    $errors[] = "Le pseudo est invalide (lettres + 0 à 3 chiffres max, 20 caractères max).";
                }

                if (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/", $password)) {
                    $errors[] = "Le mot de passe doit contenir au moins 8 caractères, avec majuscule, minuscule, chiffre et caractère spécial.";
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "L'adresse email n'est pas valide.";
                }

                // Si pas d’erreurs, on enregistre
                if (empty($errors)) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $user = new User($pseudo, $hashedPassword, $email);
                    UserRepository::addUser($user);
                    header('Location: http://localhost:8000/login');
                    exit;
                }
            }
        } catch (\Throwable $th) {
            $errors[] = "Erreur serveur : " . $th->getMessage();
        }

        require_once("../views/register.php");
    }
}
