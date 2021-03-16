<?php

namespace WjCrypto\Controller;

use Psr\Http\Message\ServerRequestInterface;
use WjCrypto\Model\AccountModel;
use WjCrypto\Model\AddressModel;
use WjCrypto\Model\UserModel;

class UserController
{
    public function login(ServerRequestInterface $request): array
    {
        /** @var array $params */
        $params = $request->getParsedBody();

        $user = new UserModel();

        if (!$user->fetchInfo($params['email'])) {
            return [
                'error' => true,
                'message' => 'Usuário desconhecido',
            ];
        }

        if (!password_verify($params['password'], $user->getPassword())) {
            return [
                'error' => true,
                'message' => 'Senha incorreta',
            ];
        }

        $_SESSION[KEY_SESSION_LOGGED_USER] = serialize($user);

        return ['error' => false];
    }

    public function fetchLoggedUserInfo(): array
    {
        if (!UserController::VerifyIfUserIsLogged()){
            return[
                'error' => true,
                'message' => 'Não existe um usuario logado'
            ];
        }

        $user = unserialize($_SESSION["logedUser"]);
        $address = new AddressModel();
        if (!$address->getInfo($user->getId())) {
            return [
                'error' => true,
                'message' => 'Houve um erro na busca dos dados',
            ];
        }

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

    public function logout(): array
    {
        session_destroy();
        return ['error' => false];
    }

    public static function VerifyIfUserIsLogged(): bool{

        if (empty($_SESSION["logedUser"])) {
            return false;
        }
        return true;
    }
}