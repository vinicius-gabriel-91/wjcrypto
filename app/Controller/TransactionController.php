<?php

namespace WjCrypto\Controller;

use Psr\Http\Message\ServerRequestInterface;
use WjCrypto\Library\DbConnection;
use WjCrypto\Model\AccountModel;
use WjCrypto\Model\LogModel;
use WjCrypto\Model\TransactionModel;

class TransactionController
{

    public function deposit(ServerRequestInterface $request): array
    {
        if (!UserController::VerifyIfUserIsLogged()){
            return[
                'error' => true,
                'message' => 'Não existe um usuario logado'
            ];
        }
        $user = unserialize($_SESSION["logedUser"]);

        $params = $request->getParsedBody();
        $amount = floatval($params['amount']);

        if($amount < 0){
            return [
                "error" => true,
                "message" => "O valor de depósito deve ser positivo",];
        }

        $account = new AccountModel();
        $account->getInfo($user->getId());
        $balance = $account->getBalance();
        $balance += $amount;
        $account->setBalance($balance);

        if (!$account->updateBalance()){
            return [
                'error' => true,
                'message' => "Falha ao realizar a transação",
            ];
        }

        $this->logTransaction($account, $params);

        return [
            "error" => false,
        ];
    }

    public function withdraw(ServerRequestInterface $request)
    {
        if (!UserController::VerifyIfUserIsLogged()){
            return[
                'error' => true,
                'message' => 'Não existe um usuario logado'
            ];
        }
        $user = unserialize($_SESSION["logedUser"]);

        $params = $request->getParsedBody();
        $amount = floatval($params['amount']);

        $account = new AccountModel();
        $account->getInfo($user->getId());
        $balance = $account->getBalance();

        if ($amount < 0 || $amount  > $balance){
            return [
                "error" => true,
                "message" => "Valor indisponivel para saque",];
        }
        $balance -= $amount;
        $account->setBalance($balance);

        if (!$account->updateBalance()){
            return [
                'error' => true,
                'message' => "Falha ao realizar a transação"
            ];
        };

        $this->logTransaction($account, $params);

        return [
            "error" => false
        ];
    }

    public function transfer(ServerRequestInterface $request)
    {
        if (!UserController::VerifyIfUserIsLogged()){
            return[
                'error' => true,
                'message' => 'Não existe um usuario logado'
            ];
        }
        $user = unserialize($_SESSION["logedUser"]);

        $params = $request->getParsedBody();
        $amount = floatval($params['amount']);

        $originAccount = new AccountModel();
        $originAccount->getInfo($user->getId());

        if ($params['targetAcountId'] == $originAccount->getCode()){
            return [
                "error" => true,
                "message" => "A conta de destino não pode ser igual a de origem"
                ];
        }

        $balance = $originAccount->getBalance();
        if ($amount < 0 || $amount  > $balance){
            return [
                "error" => true,
                "message" => "Valor indisponivel para transferencia",];
        }
        $balance -= $amount;
        $originAccount->setBalance($balance);

        if (!$originAccount->updateBalance()){
            return [
                'error' => true,
                'message' => "Falha ao realizar a transação"
            ];
        };

        $targetAcount = new AccountModel();

        if (!$targetAcount->getInfoByCode($params['targetAcountId'])){
            return [
                "error" => true,
                "message" => "Conta de destino invalida"
            ];
        }

        $targetAcountBalance = $targetAcount->getBalance();
        $targetAcountBalance += $amount;
        $targetAcount->setBalance($targetAcountBalance);

        if (!$targetAcount->updateBalance()){
            return [
                'error' => true,
                'message' => "Falha ao realizar a transação"
            ];
        }

        $this->logTransaction($originAccount, $params, $targetAcount->getAccountId());

        return [
            "error" => false,
        ];

    }

    public function getTransactionList()
    {
        $user = unserialize($_SESSION['logedUser']);
        $account = new AccountModel();
        $account->getInfo($user->getId());
        $request = new TransactionModel();
        $request->setAccountId($account->getAccountId());
        $return = $request->getList();
        return [
            'error' => false,
            'transactions' => $return,
        ];
    }

    private function logTransaction($account, $params, $targetAccount = null)
    {

        $log = new TransactionModel();
        $log->setTypeId(intval($params['transactionId']));
        $log->setAccountId($account->getAccountId());
        $log->setValue($params['amount']);
        $log->setTargetId($targetAccount);
        if(!$log->addTransaction()){
            return false;
        }
    }

}