<?php

class AccountController
{

    public function __construct()
    {
        if ($_POST["action"] == "login"){
            $this->login();
        } elseif ($_POST["action"] == "newUser"){
            $this->newUser();
        } elseif ($_POST["action"] == "profile"){
            $this->profile();
        } elseif ($_POST["action"] == "updateAddress"){
            $this->updateAddress();
        } elseif ($_POST["action"] == "logout"){
            $this->logout();
        } elseif ($_POST["action"] == "backToHome"){
            $this->backToHome();
        }
    }

    public function login()
    {
        $user = new UserModel();
        $user->setEmail($_POST["email"]);
        $user->setPassword($_POST["password"]);
        $user->getInfo();
        $_SESSION["logedUser"] = serialize($user);

        $account = new AccountModel();
        $account->getInfo($user->getId());
        $_SESSION["account"] = serialize($account);


        $return = [
            "name" => $user->getName(),
            "account" => $account->getCode(),
            "balance" => $account->getBalance()
        ];

        echo json_encode($return);
    }

    public function newUser()
    {
        $user = new UserModel();
        $user->setName($_POST["name"]);
        $user->setSurname($_POST["surname"]);
        $user->setEmail($_POST["email"]);
        $user->setPassword($_POST["password"]);
        $user->setBusiness($_POST["isBusiness"]);
        $user->setTaxvat($_POST["taxvat"]);
        $user->setDocNumber($_POST["docNumber"]);

        if($user->getBusiness() == 1){
            $user->setCorporateName($_POST["corporateName"]);
            $userInformation[] = ["corporateName" => $user->getCorporateName()];
        }

        $user->adduser();
        $user->getInfo();

        $address = new AddressModel();
        $address->setUserId($user->getId());
        $address->setStreet($_POST["street"]);
        $address->setStreetNumber($_POST["streetNumber"]);
        $address->setStreetNumberAdition($_POST["streetNumberAdition"]);
        $address->setPostalCode($_POST["postalCode"]);
        $address->setCity($_POST["city"]);
        $address->setCountry($_POST["country"]);
        $address->setPhoneNumber($_POST["phoneNumber"]);

        $address->addAddress();


        $account = new AccountModel();
        $account->addAccount(0,$user->getId());

        echo"Usuario criado com sucesso!";
    }

    public function profile()
    {
        $user = unserialize($_SESSION["logedUser"]);

        $address = new AddressModel();
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

    }

    public function updateAddress()
    {
        $user = unserialize($_SESSION["logedUser"]);
        $address = unserialize($_SESSION["address"]);

        $address->setStreet($_POST["street"]);
        $address->setStreetNumber($_POST["streetNumber"]);
        $address->setStreetNumberAdition($_POST["streetNumberAdition"]);
        $address->setPostalCode($_POST["postalCode"]);
        $address->setCity($_POST["city"]);
        $address->setCountry($_POST["country"]);
        $address->setPhoneNumber($_POST["phoneNumber"]);
        $address->updateAddress($user->getId());

        echo "Endereço atualizado com sucesso!";

    }

    public function logout()
    {
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

