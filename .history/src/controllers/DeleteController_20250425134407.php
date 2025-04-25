<?php

class DeleteController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['pseudo']) || empty($_SESSION['pseudo'])) {
            header('Location: /');
            exit;
        }

        if (isset($_POST['delete'])) {
            $id = intval(htmlspecialchars($_POST['delete']));
            $post = PostRepository::getPostById($id);
            try {
                if (PostRepository::deletePost($id)) {
                    header('Location: /');
                    exit;
                } else {
                    $errors[] = "Erreur lors de la suppression du post.";
                }
            } catch (Exception $e) {
                $errors[] = "Une erreur est survenue : " . $e->getMessage();
                error_log($e);
            }
        }

        require_once("../views/main.php");
    }
}
