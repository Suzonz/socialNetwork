<?php

class EditController extends Controller
{
    public function index()
    {

        if (!isset($_SESSION['pseudo'])) {
            header('Location: /login');
            exit;
        }

        // Si l'utilisateur arrive après avoir cliqué sur le bouton "modifier"
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editView'])) {
            $postId = $_POST['editView'];
            $postData = PostRepository::getPostById($postId);

            // Vérifie que le post existe et appartient à l'utilisateur
            if ($postData && $postData['author'] === $_SESSION['pseudo']) {
                $post = new Post($postData['title'], $postData['content'], $postData['author']);
                $post->setId($postData['id']);
                require 'views/edit.php';
            } else {
                $errors[] = "Le post n'a pas été trouvé ou vous n'êtes pas l'auteur.";
                require 'views/edit.php';
            }
        }

        // Traitement du formulaire d'édition
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
            $postId = $_POST['update'];
            $title = trim($_POST['new_post_title']);
            $content = trim($_POST['new_post_content']);

            $postData = PostRepository::getPostById($postId);

            if (!$postData || $postData['author'] !== $_SESSION['pseudo']) {
                $errors[] = "Modification non autorisée.";
                require 'views/edit.php';
                return;
            }

            // Validation
            if (empty($title)) {
                $errors[] = "Le titre ne peut pas être vide.";
            }
            if (empty($content)) {
                $errors[] = "Le contenu ne peut pas être vide.";
            }

            if (!isset($errors)) {
                $updated = PostRepository::editPost($postId, $title, $content);
                if ($updated) {
                    header("Location: /");
                    exit;
                } else {
                    $errors[] = "Erreur lors de la mise à jour du post.";
                }
            }

            $post = new Post($title, $content, $_SESSION['pseudo']);
            $post->setId($postId);
            require 'views/edit.php';
        }
    }
    //     $errors = [];

    //     if (!isset($_SESSION['pseudo']) || empty($_SESSION['pseudo'])) {
    //         $errors[] = "Vous devez être connecté pour modifier un post.";
    //         require_once("../views/main.php");
    //         return;
    //     }

    //     // Vérifier si le post ID est passé dans POST et que l'utilisateur est l'auteur
    //     if (isset($_POST['editView'])) {
    //         $id = $_POST['editView'];

    //         // Récupérer le post par ID

    //         // Vérifier que le post existe et que l'utilisateur est l'auteur
    //         if (!$post || $post->getAuthor() !== $_SESSION['pseudo']) {
    //             $errors[] = "Ce post n'existe pas ou vous n'êtes pas l'auteur.";
    //         }
    //     } else {
    //         $errors[] = "Aucun post sélectionné.";
    //     }

    //     // Modification du post
    //     if (isset($_POST['update']) && isset($_POST['new_post_title']) && isset($_POST['new_post_content'])) {
    //         $id = $_POST['update'];
    //         $title = htmlspecialchars($_POST['new_post_title']);
    //         $content = htmlspecialchars($_POST['new_post_content']);
    //         $author = $_SESSION['pseudo'];

    //         if (empty($title) || empty($content)) {
    //             $errors[] = "Tous les champs doivent être remplis.";
    //         }

    //         if (empty($errors)) {
    //             try {
    //                 if (PostRepository::editPost($id, $title, $content, $author)) {
    //                     header('Location: /');
    //                     exit;
    //                 } else {
    //                     $errors[] = "Erreur lors de la modification du post.";
    //                 }
    //             } catch (Exception $e) {
    //                 $errors[] = "Une erreur est survenue : " . $e->getMessage();
    //                 error_log($e);
    //             }
    //         }
    //     }

    //     require_once("../views/edit.php");
    // }
}
?>
