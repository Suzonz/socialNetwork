<?php

class Db {
    private static $instance;

    /**
     * Connexion à la base. Les réglages viennent des variables d'environnement
     * (DB_HOST, DB_NAME, DB_USER, DB_PASSWORD) chez l'hébergeur ; sans elles,
     * ce sont les réglages locaux (Laragon : localhost, yolo, root, sans mot de passe).
     */
    protected static function getInstance(){
        if (self::$instance == null) {
            $host = getenv('DB_HOST') ?: 'localhost';
            $name = getenv('DB_NAME') ?: 'yolo';
            $user = getenv('DB_USER') ?: 'root';
            $password = getenv('DB_PASSWORD') ?: '';
            try {
                self::$instance = new PDO("mysql:host=$host;dbname=$name;charset=utf8mb4", $user, $password);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                error_log($e->getMessage());
                // Le détail de l'erreur n'est montré qu'en local
                die(APP_DEBUG ? $e->getMessage() : "La base de données est indisponible.");
            }
        }
        return self::$instance;
    }

    protected static function disconnect(){
        self::$instance = null;
    }
}
