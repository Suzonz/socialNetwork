<?php

abstract class UserRepository extends Db
{
    // Requêtes préparées : les valeurs tapées par l'utilisateur ne sont
    // jamais collées dans le SQL (pas d'injection SQL possible)

    public static function addUser(User $user)
    {
        $stmt = self::getInstance()->prepare("INSERT INTO user (pseudo, password, email) VALUES (?, ?, ?)");
        return $stmt->execute([$user->getPseudo(), $user->getPassword(), $user->getEmail()]);
    }

    public static function pseudoExists($pseudo)
    {
        $stmt = self::getInstance()->prepare("SELECT 1 FROM user WHERE pseudo = ?");
        $stmt->execute([$pseudo]);
        return (bool) $stmt->fetchColumn();
    }

    public static function deleteUser($id)
    {
        $stmt = self::getInstance()->prepare("DELETE FROM user WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /** Le pseudo exact enregistré et son mot de passe chiffré, ou null. */
    public static function findForLogin(User $user)
    {
        $stmt = self::getInstance()->prepare("SELECT pseudo, password FROM user WHERE pseudo = ?");
        $stmt->execute([$user->getPseudo()]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }
}
