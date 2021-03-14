<?php

namespace Wjcrypto\Controller;

use WjCrypto\Library\DbConnection;
use WjCrypto\Model\AccountModel;
use WjCrypto\Model\LogModel;
use WjCrypto\Model\TransactionModel;

class TransactionController
{
    private $logActivity;
    private $connection;
    private $params;

    public function __construct(LogModel $logModel, DbConnection $connection)
    {

        $this->logActivity = $logModel;
        $this->connection = $connection;
        $this->params = json_decode($_POST['params'], true);

        if ($this->params["action"] == "Deposito") {
            $this->deposit($this->params["amount"]);
        } elseif ($this->params["action"] == "Saque") {
            $this->withdraw($this->params["amount"]);
        } elseif ($this->params["action"] == "Transferencia") {
            $this->transfer($this->params["amount"], $this->params["targetAcountId"]);
        } elseif ($this->params["action"] == "getTransactionList"){
            $this->getTransactionList();
        }
    }

    public function deposit($amount)
    {
        $amount = floatval($amount);
        if($amount < 0){
            echo("O valor de depósito deve ser positivo");
            return;
        }
        $user = unserialize($_SESSION["logedUser"]);
        $account = unserialize($_SESSION["account"]);
        $account->getInfo($user->getId());
        $balance = $account->getBalance();
        $balance += $amount;
        $account->setBalance($balance);
        $account->updateBalance();

        $_SESSION["account"] = serialize($account);
        if($this->params["action"] == 'Deposito') {
            $this->logTransaction($amount);
        }
        $return = ["balance" => $account->getBalance()];
        echo json_encode($return);
        $this->logActivity->logActivity($user->getId());
        return true;
    }

    public function withdraw($amount)
    {
        $amount = floatval($amount);
        $user = unserialize($_SESSION["logedUser"]);
        $account = unserialize($_SESSION["account"]);
        $account->getInfo($user->getId());
        $balance = $account->getBalance();

        if ($amount < 0 || $amount  > $balance){
            echo("Valor indisponivel");
            return;
        }

        $balance -= $amount;
        $account->setBalance($balance);
        $account->updateBalance();

        if($this->params["action"] == "Saque") {
            $this->logTransaction($amount);
        }
        $_SESSION["account"] = serialize($account);
        $return = ["balance" => $account->getBalance()];
        echo json_encode($return);
        $this->logActivity->logActivity($user->getId());
        return true;
    }

    public function transfer($amount, $targetAcountCode)
    {
        $account = unserialize($_SESSION["account"]);
        if ($targetAcountCode == $account->getCode()){
            echo "Conta invalida";
            return;
        }
        if ($this->withdraw($this->params["amount"])) {
            $targetAcount = new AccountModel($this->connection);
            $targetAcount->getInfoByCode($targetAcountCode);
            $balance = $targetAcount->getBalance();
            $balance += $amount;
            $targetAcount->setBalance($balance);
            $targetAcount->updateBalance();
            $this->logTransaction($amount, $targetAcount->getAccountId());
        }
    }

    public function getTransactionList()
    {
        $account = unserialize($_SESSION["account"]);
        $request = new TransactionModel($this->connection);
        $request->setAccountId($account->getAccountId());
        $return = $request->getList();
        echo json_encode($return);
    }

    private function logTransaction($amount, $targetAccountId = null)
    {
        $account = unserialize($_SESSION["account"]);
        $log = new TransactionModel($this->connection);
        $log->getTypeId($this->params["action"]);
        $log->setAccountId($account->getAccountId());
        $log->setValue($amount);
        $log->setTargetId($targetAccountId);
        $log->addTransaction();
    }

}