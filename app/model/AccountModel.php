<?php
ini_set('display_errors', 'on');

class AccountModel
{
    private $accountId;
    private $connection;
    private $code;
    private $balance;

    public function __construct()
    {
        $this->connection = new PDO("mysql:host=localhost;port=3306;dbname=wjcrypto", "vinicius", "webjump");
    }

    public function getAccountId()
    {
        return $this->accountId;
    }

    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    public function addAccount($balance = 0, $userId)
    {
        $code = $this->createCode($userId);
        $stmt = $this->connection->prepare("INSERT INTO account (code, balance, user_id) VALUE (:code, :balance, :user_id)");
        $stmt->execute([
            ":code" => $code,
            ":balance" => $balance,
            ":user_id" => $userId
            ]);
    }

    public function getList($userId):array
    {
        $stmt = $this->connection->prepare(
                                    "SELECT
                                                id,
                                                code,
                                                balance
                                           FROM
                                                account
                                           WHERE 
                                                user_id = :userId
                                           ");
        $stmt->execute([":userId" => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInfo($accountId)
    {
        $stmt = $this->connection->prepare("
                                            SELECT
                                                code,
                                                balance,
                                                user_id
                                            FROM
                                                account
                                            WHERE
                                                id = :=accountId
                                            ");
        $stmt->execute([
                        ":accountId" => $accountId
                        ]);
        $select = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $selectResult = $select["0"];
        $this->accountId = $selectResult["id"];
        $this->code = $selectResult["code"];
        $this->balance = $selectResult["balance"];
    }

    public function updateBalance()
    {
        $stmt = $this->connection->prepare(
                                    "UPDATE
                                                account
                                           SET 
                                                balance = :balance
                                           WHERE
                                                id = :accountId
                                          ");
        $stmt->execute([":balance" => $this->balance, ":accountId" => $this->accountId]);
    }

    public function deleteAccount()
    {
        $stmt = $this->connection->prepare("
                                            DELETE
                                            FROM
                                                account
                                            WHERE
                                                id = :accountId
                                           ");
        $stmt->execute([":userId" => $this->accountId]);
    }

    private function createCode($userId)
    {
        $data = date("dmy");
        $code = $userId.$data;
        return $code;
    }
}
