<?php
require_once __DIR__ . "/../model/UserModel.php";
header("Content-Type:application/json");

class AccountController
{
    public $user;

    public function __construct()
    {

            if ($_POST["userId"]){
            $this->user = $this->getUser();
        }

    }
    public function getUser()
    {
        $user = new UserModel();
        $user->setEmail($_POST["email"]);
        $user->setPassword($_POST["password"]);
        $user->getInfo();
        return $user;
    }

}

