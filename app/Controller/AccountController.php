<?php

namespace WjCrypto\Controller;

use Psr\Http\Message\ServerRequestInterface;
use WjCrypto\Model\AccountModel;
use WjCrypto\Model\AddressModel;
use WjCrypto\Model\LogModel;
use WjCrypto\Model\UserModel;

class AccountController
{

    public function updateAddress(ServerRequestInterface $request): array
    {
        if (!UserController::VerifyIfUserIsLogged()){
            return[
                'error' => true,
                'message' => 'Não existe um usuario logado'
            ];
        }
        $params = $request->getParsedBody();
        $user = unserialize($_SESSION["logedUser"]);
        $address = new AddressModel();
        $address->getInfo($user->getId());

        $address->setStreet($params["street"]);
        $address->setStreetNumber($params["streetNumber"]);
        $address->setStreetNumberAdition($params["streetNumberAdition"]);
        $address->setPostalCode($params["postalCode"]);
        $address->setCity($params["city"]);
        $address->setCountry($params["country"]);
        $address->setPhoneNumber($params["phoneNumber"]);

        if (!$address->updateAddress($user->getId())){
            return[
                'error' => true,
                'message' => 'Erro na atualização de dados'
            ];
        }
        return ['error' => false];
    }

    public function getAccount(){

        if (!UserController::VerifyIfUserIsLogged()){
            return[
                'error' => true,
                'message' => 'Não existe um usuario logado'
            ];
        }
        $user = unserialize($_SESSION["logedUser"]);
        $account = new AccountModel();
        if (!$account->getInfo($user->getId())){
            return[
                'error' => true,
                'message' => 'Houve uma falha na busca da conta'
            ];
        }
        return [
            'error' => false,
            'account' => $account->toArray(),
            ];



    }

}

