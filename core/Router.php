<?php

class Router
{
    public function start()
    {
        try {
            // Le chemin seul, sans « ?… » : /login?x=1 → /login
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
            if ($path !== "/") {
                $controller = ucfirst(explode('/', $path)[1]) . "Controller";
                // Seulement un vrai contrôleur du site
                if (class_exists($controller) && is_subclass_of($controller, 'Controller')) {
                    $instance = new $controller();
                    $instance->index();
                }else{
                    http_response_code(404);
                    include_once '../views/error.php';
                }
            }else{
                $instance = new MainController();
                $instance->index();
            }
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            http_response_code(500);
            // Le détail de l'erreur n'est montré qu'en local
            echo APP_DEBUG ? $th->getMessage() : "Une erreur est survenue.";
        }
    }
}
