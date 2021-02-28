<?php

class AccountModel
{
    private $connection;
    private $code;
    private $balance;

    public function __construct()
    {
        $connection = new DbConnection();
        $this->connection = $connection->connection();
    }

    public function __toString()
    {
        return json_encode(array(
            $this->code,
            $this->balance,
        ));
    }

    public function __sleep()
    {
        return ["code", "balance"];
    }

    public function __wakeup()
    {
        $connection = new DbConnection();
        $this->connection = $connection->connection();
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getBalance()
    {
        return $this->balance;
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

    public function getInfo($userId)
    {
        $stmt = $this->connection->prepare("
                                            SELECT
                                                code,
                                                balance
                                            FROM
                                                account
                                            WHERE
                                                user_id = :userId
                                            ");
        $stmt->execute([
                        ":userId" => $userId
                        ]);
        $select = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $selectResult = $select["0"];
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
