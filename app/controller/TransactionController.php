<?php


class TransactionController
{
    public function __construct()
    {
        if ($_POST["action"] == "deposit") {
            $this->deposit($_POST["amount"]);
        } elseif ($_POST["action"] == "withdraw") {
            $this->withdraw($_POST["amount"]);
        } elseif ($_POST["action"] == "transfer") {
            $this->transfer($_POST["amount"], $_POST["targetAcountId"]);
        } elseif ($_POST["action"] == "getTransactionList"){
            $this->getTransactionList();
        }
    }

    public function deposit($amount)
    {
        if($amount < 0){
            echo("O valor de depósito deve ser positivo");
            return;
        }

        $amount = floatval($amount);
        $user = unserialize($_SESSION["logedUser"]);
        $account = unserialize($_SESSION["account"]);
        $account->getInfo($user->getId());
        $balance = $account->getBalance();
        $balance += $amount;
        $account->setBalance($balance);
        $account->updateBalance();

        $_SESSION["account"] = serialize($account);
        if($_POST["action"] == "deposit") {
            $this->logTransaction($amount);
        }
        $return = ["balance" => $account->getBalance()];
        echo json_encode($return);
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

        if($_POST["action"] == "withdraw") {
            $this->logTransaction($amount);
        }
        $_SESSION["account"] = serialize($account);
        $return = ["balance" => $account->getBalance()];
        echo json_encode($return);
        return true;
    }

    public function transfer($amount, $targetAcountCode)
    {
        $account = unserialize($_SESSION["account"]);
        if ($targetAcountCode == $account->getCode()){
            echo "Conta invalida";
            return;
        }
        if ($this->withdraw($_POST["amount"])) {
            $targetAcount = new AccountModel();
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
        $request = new TransactionModel();
        $request->setAccountId($account->getAccountId());
        $return = $request->getList();
        var_dump($return);
    }

    private function logTransaction($amount, $targetAccountId = null)
    {
        $account = unserialize($_SESSION["account"]);
        $log = new TransactionModel();
        $log->getTypeId($_POST["action"]);
        $log->setAccountId($account->getAccountId());
        $log->setValue($amount);
        $log->setTargetId($targetAccountId);
        $log->addTransaction();
    }


}