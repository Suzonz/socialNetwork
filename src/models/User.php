<?php

class User extends UserRepository
{
    private $pseudo;
    private $password;
    private $email;

    /* ----------- CONSTRUCTOR -----------*/
    public function __construct($pseudo, $password, $email)
    {
        $this->setPseudo($pseudo);
        $this->setPassword($password);
        $this->setEmail($email);
    }

    /* ----------- GETTERS AND SETTERS -----------*/

    public function getPseudo()
    {
        return $this->pseudo;
    }
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
    }

    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /* ----------- METHODS -----------*/


    // public static function getPasswordFromForm()
    // {
    //     return isset($_POST['password']) ? $_POST['password'] : null;
    // }
}
