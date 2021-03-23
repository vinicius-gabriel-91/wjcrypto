<?php

namespace WjCrypto\Controller;

use Psr\Http\Message\ServerRequestInterface;
use WjCrypto\Library\AuthManager;
use WjCrypto\Model\AccountModel;
use WjCrypto\Model\AddressModel;
use WjCrypto\Model\LogModel;
use WjCrypto\Model\UserModel;

class UserController
{
    public function login(ServerRequestInterface $request): array
    {
        LogModel::create($request, 'Login Realizado');

        return [
            'error' => false
        ];
    }

    public function fetchLoggedUserInfo(ServerRequestInterface $request): array
    {
        $user = AuthManager::getLoggedUser();

        $address = new AddressModel();
        if (!$address->getInfo($user->getId())) {
            return [
                'error' => true,
                'message' => 'Houve um erro na busca dos dados',
            ];
        }

        LogModel::create($request, 'Acesso ao perfil');

        return [
            'error' => false,
            'user' => $user->toArray(),
            'address' => $address->toArray(),
        ];
    }

    public function newUser(ServerRequestInterface $request): array
    {
        $params = $request->getParsedBody();
        $hash = password_hash($params["password"], PASSWORD_DEFAULT);
        $user = new UserModel();

        if ($user->verifyIfUserExists($params["email"])){
            return [
                'error' => true,
                'message' => 'Ja existe uma conta cadastrada com este email'
            ];
        }

        $user->setName($params["name"]);
        $user->setSurname($params["surname"]);
        $user->setEmail($params["email"]);
        $user->setPassword($hash);
        $user->setBusiness($params["isBusiness"]);
        $user->setTaxvat($params["taxvat"]);
        $user->setDocNumber($params["docNumber"]);

        if ($user->getBusiness() == 1){
            $user->setCorporateName($params["corporateName"]);
            $userInformation[] = ["corporateName" => $user->getCorporateName()];
        }

        if (!$user->adduser()) {
            return [
                'error' => true,
                'message' => 'Dados invalidos do usuario',
            ];
        }

        $user->fetchInfo($user->getEmail());

        $address = new AddressModel();
        $address->setUserId($user->getId());
        $address->setStreet($params["street"]);
        $address->setStreetNumber($params["streetNumber"]);
        $address->setStreetNumberAdition($params["streetNumberAdition"]);
        $address->setPostalCode($params["postalCode"]);
        $address->setCity($params["city"]);
        $address->setCountry($params["country"]);
        $address->setPhoneNumber($params["phoneNumber"]);

        if (!$address->addAddress()) {
            return [
                'error' => true,
                'message' => 'Dados invalidos do endereço',
            ];
        }

        $account = new AccountModel();

        if (!$account->addAccount(0, $user->getId())){
            return [
                'error' => true,
                'message' => 'Erro ao criar conta',
            ];
        }

        return ['error' => false];
    }

    public function logout(ServerRequestInterface $request): array
    {
        $user = AuthManager::getLoggedUser();
        $log = new LogModel($request);
        $log->logActivity($user->getId());
        session_destroy();
        return ['error' => false, 'server' => $_SERVER['HTTP_USER_AGENT']];
    }

    public static function VerifyIfUserIsLogged(): bool{

        if (empty($_SESSION["logedUser"])) {
            return false;
        }
        return true;
    }
}