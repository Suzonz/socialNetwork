<?php

class DeleteController extends Controller
{
   // Dans DeleteController
public function index()
{
    if (!isset($_SESSION['pseudo'])) {
        header('Location: /');
        exit;
    }

    if (isset($_POST['delete'])) {
        $id = intval($_POST['delete']);
        $post = PostRepository::getPostById($id);
        
        // Vérification propriétaire + suppression
        if ($post && $post['author'] === $_SESSION['pseudo']) {
            if (PostRepository::deletePost($id)) {
                header('Location: /');
                exit;
            }
        }
        
        $_SESSION['error'] = "Action non autorisée";
    }

    require_once("../views/main.php");
}

}
