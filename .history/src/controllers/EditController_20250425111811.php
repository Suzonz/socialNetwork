<?php

class EditController extends Controller
{
    public function index()
    {
        $errors = [];

        if (!isset($_SESSION['pseudo']) || empty($_SESSION['pseudo'])) {
            $errors[] = "Vous devez être connecté pour modifier un post.";
            require_once("../views/main.php");
            return;
        }

        // Vérifier si le post ID est passé dans POST et que l'utilisateur est l'auteur
        if (isset($_POST['editView'])) {
            $id = $_POST['editView'];

            // Récupérer le post par ID
            $post = PostRepository::getPostById($id);

            // Vérifier que le post existe et que l'utilisateur est l'auteur
            if (!$post || $post->getAuthor() !== $_SESSION['pseudo']) {
                $errors[] = "Ce post n'existe pas ou vous n'êtes pas l'auteur.";
            }
        } else {
            $errors[] = "Aucun post sélectionné.";
        }

        // Modification du post
        if (isset($_POST['update']) && isset($_POST['new_post_title']) && isset($_POST['new_post_content'])) {
            $id = $_POST['update'];
            $title = htmlspecialchars($_POST['new_post_title']);
            $content = htmlspecialchars($_POST['new_post_content']);
            $author = $_SESSION['pseudo'];

            if (empty($title) || empty($content)) {
                $errors[] = "Tous les champs doivent être remplis.";
            }

            if (empty($errors)) {
                try {
                    if (PostRepository::editPost($id, $title, $content, $author)) {
                        header('Location: /');
                        exit;
                    } else {
                        $errors[] = "Erreur lors de la modification du post.";
                    }
                } catch (Exception $e) {
                    $errors[] = "Une erreur est survenue : " . $e->getMessage();
                    error_log($e);
                }
            }
        }

        require_once("../views/edit.php");
    }
}
?>
