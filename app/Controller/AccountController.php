<?php

namespace WjCrypto\Controller;

use WjCrypto\Library\DbConnection;
use WjCrypto\Model\AccountModel;
use WjCrypto\Model\AddressModel;
use WjCrypto\Model\LogModel;
use WjCrypto\Model\UserModel;

class AccountController
{
    private $logActivity;
    private $connection;
    private $params;

    public function __construct(LogModel $logModel, DbConnection $connection)
    {
        $this->logActivity = $logModel;
        $this->connection = $connection;
        $this->params = json_decode($_POST['params'], true);

        if ($this->params["action"] == "login"){
            $this->login();
        } elseif ($this->params["action"] == "newUser"){
            $this->newUser();
        } elseif ($this->params["action"] == "profile"){
            $this->profile();
        } elseif ($this->params["action"] == "updateAddress"){
            $this->updateAddress();
        } elseif ($this->params["action"] == "logout"){
            $this->logout();
        } elseif ($this->params["action"] == "backToHome"){
            $this->backToHome();
        }
    }

    public function login()
    {
        $user = new UserModel($this->connection);
        if (password_verify($this->params["password"], $user->getPassword($this->params["email"]))) {
            $user->setEmail($this->params["email"]);
            $user->getInfo();
            $_SESSION["logedUser"] = serialize($user);

            $account = new AccountModel($this->connection);
            $account->getInfo($user->getId());
            $_SESSION["account"] = serialize($account);

            $return = [
                "name" => $user->getName(),
                "account" => $account->getCode(),
                "balance" => $account->getBalance()
            ];
            echo json_encode($return);
        } else{
            $message = "Email ou senha invalidos";
            echo json_encode($message);
        }
        $this->logActivity->logActivity($user->getId());
    }

    public function newUser()
    {
        var_dump($this->params);
        $hash = password_hash($this->params["password"], PASSWORD_DEFAULT);
        $user = new UserModel();
        $user->setName($this->params["name"]);
        $user->setSurname($this->params["surname"]);
        $user->setEmail($this->params["email"]);
        $user->setPassword($hash);
        $user->setBusiness($this->params["isBusiness"]);
        $user->setTaxvat($this->params["taxvat"]);
        $user->setDocNumber($this->params["docNumber"]);

        if($user->getBusiness() == 1){
            $user->setCorporateName($this->params["corporateName"]);
            $userInformation[] = ["corporateName" => $user->getCorporateName()];
        }

        $user->adduser();
        $user->getInfo();

        $address = new AddressModel();
        $address->setUserId($user->getId());
        $address->setStreet($this->params["street"]);
        $address->setStreetNumber($this->params["streetNumber"]);
        $address->setStreetNumberAdition($this->params["streetNumberAdition"]);
        $address->setPostalCode($this->params["postalCode"]);
        $address->setCity($this->params["city"]);
        $address->setCountry($this->params["country"]);
        $address->setPhoneNumber($this->params["phoneNumber"]);

        $address->addAddress();


        $account = new AccountModel();
        $account->addAccount(0,$user->getId());

        echo"Usuario criado com sucesso!";
    }

    public function profile()
    {
        $user = unserialize($_SESSION["logedUser"]);
        $address = new AddressModel($this->connection);
        $address->getInfo($user->getId());
        $_SESSION["address"] = serialize($address);

        $userInfo = [
            "name" => $user->getName(),
            "surname" => $user->getSurname(),
            "taxvat" => $user->getTaxvat(),
            "docNumber" => $user->getDocumentNumber()
        ];
        if ($user->getBusiness() == 1){
            $return[] = $user->getCorporateName();
        }

        $userAddress = [
            "street" => $address->getStreet(),
            "streetNumber" => $address->getStreetNumber(),
            "streetNumberAdition" => $address->getStreetNumberAdition(),
            "postalCode" => $address->getPostalCode(),
            "city" => $address->getCity(),
            "country" => $address->getCountry(),
            "phoneNumber" => $address->getPhoneNumber()
        ];

        $return = [$userInfo, $userAddress];
        echo json_encode($return);
        $this->logActivity->logActivity($user->getId());

    }

    public function updateAddress()
    {
        $user = unserialize($_SESSION["logedUser"]);
        $address = unserialize($_SESSION["address"]);

        $address->setStreet($this->params["street"]);
        $address->setStreetNumber($this->params["streetNumber"]);
        $address->setStreetNumberAdition($this->params["streetNumberAdition"]);
        $address->setPostalCode($this->params["postalCode"]);
        $address->setCity($this->params["city"]);
        $address->setCountry($this->params["country"]);
        $address->setPhoneNumber($this->params["phoneNumber"]);
        $address->updateAddress($user->getId());

        echo "Endereço atualizado com sucesso!";
        $this->logActivity->logActivity($user->getId());
    }

    public function logout()
    {
        $user = unserialize($_SESSION["logedUser"]);
        $this->logActivity->logActivity($user->getId());
        session_destroy();
    }

    public function backToHome()
    {
        $user = unserialize($_SESSION["logedUser"]);
        $account = unserialize($_SESSION["account"]);
        $return = [
            "name" => $user->getName(),
            "account" => $account->getCode(),
            "balance" => $account->getBalance()
        ];
        echo json_encode($return);
    }

    public function changePassword()
    {

    }

    public function changeEmail()
    {

    }
}

