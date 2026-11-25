<?php

class PostController extends Controller
{
    public function index()
    {
        $errors = [];

        if (!isset($_SESSION['pseudo']) || empty($_SESSION['pseudo'])) {
            $errors[] = "Vous devez être connecté pour publier.";
            require_once("../views/main.php");
            return;
        }

        if (isset($_POST['new_post_title']) && isset($_POST['new_post_content'])) {
            // Enregistré tel quel : l'échappement se fait à l'affichage (htmlspecialchars dans les vues)
            $title = trim($_POST['new_post_title']);
            $content = trim($_POST['new_post_content']);
            $author = $_SESSION['pseudo'];

            if (empty($title) || empty($content)) {
                $errors[] = "Tous les champs sont requis.";
            }

            if (empty($errors)) {
                $post = new Post($title, $content, $author);
                try {
                    if (PostRepository::addPost($post)) {
                        header('Location: /');
                        exit;
                    } else {
                        $errors[] = "Erreur lors de la publication du post.";
                    }
                } catch (Exception $e) {
                    $errors[] = APP_DEBUG ? "Une erreur est survenue : " . $e->getMessage() : "Une erreur est survenue.";
                    error_log($e);
                }
            }
        }

        require_once("../views/post.php");
    }
}
?>

