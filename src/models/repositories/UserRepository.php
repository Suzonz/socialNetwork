<?php

abstract class UserRepository extends Db
{
    private static function request($request)
    {
        $result = self::getInstance()->query($request);
        return $result;
    }

    public static function addUser(User $user)
    {
        $pseudo = $user->getPseudo();
        $password = $user->getPassword();
        $email = $user->getEmail();

        $sql = "INSERT into user (pseudo, password, email) VALUES('$pseudo', '$password', '$email')";
        return self::request($sql);
    }

    public static function deleteUser($id)
    {
        $sql = "DELETE FROM user WHERE id = $id";
        return self::request($sql);
    }

    public static function checkPassword(User $user)
    {
        $pseudo = $user->getPseudo();
        $sql = "SELECT password FROM user where pseudo = '$pseudo'";
        $stmt = self::request($sql); // <- retourne un objet PDOStatement
        $result = $stmt->fetch();    // <- on récupère la ligne (fetch une seule)

        return $result ? $result['password'] : null;
    }
}
