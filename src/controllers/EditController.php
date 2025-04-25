<?php

class EditController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['pseudo'])) {
            header('Location: /login');
            exit;
        }

        // Étape 1 : Affichage du formulaire
        if (isset($_POST['editView'])) {
            $postId = $_POST['editView'];
            $postData = PostRepository::getPostById($postId);

            if ($postData && $postData['author'] === $_SESSION['pseudo']) {
                $post = new Post($postData['title'], $postData['content'], $postData['author'], $postData['id'], $postData['created_at']);
            } else {
                $errors[] = "Le post n'existe pas ou vous n'êtes pas autorisé à le modifier.";
                $post = null;
            }
            require_once("../views/edit.php");
            return;
        }

        // Étape 2 : Traitement de la soumission du formulaire
        if (isset($_POST['update'])) {
            $postId = $_POST['update'];
            $title = trim($_POST['new_post_title']);
            $content = trim($_POST['new_post_content']);

            $postData = PostRepository::getPostById($postId);

            if (!$postData || $postData['author'] !== $_SESSION['pseudo']) {
                $errors[] = "Modification non autorisée.";
                $post = null;
                require_once('../views/edit.php');
                return;
            }

            $post = new Post($title, $content, $_SESSION['pseudo'], $postId, $postData['created_at']);
            $errors = $post->validate();

            if (empty($errors)) {
                $success = PostRepository::editPost($postId, $title, $content);
                if ($success) {
                    header('Location: /');
                    exit;
                } else {
                    $errors[] = "Échec de la mise à jour du post.";
                }
            }
            require_once("../views/edit.php");
        }
    }
}

