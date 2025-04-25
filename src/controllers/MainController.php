<?php
class MainController extends Controller
{
    public function index()
    {
        $posts = PostRepository::getAllPosts();

        require_once("../views/main.php");
    }
}
