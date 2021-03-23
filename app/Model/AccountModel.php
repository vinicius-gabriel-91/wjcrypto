<?php

namespace WjCrypto\Model;

use PDO;
use WjCrypto\Library\DbConnection;

class AccountModel
{
    private $connection;
    private $code;
    private $balance;
    private $accountId;

    public function __construct()
    {
        $this->connection = DbConnection::getInstance();
    }

    public function __toString()
    {
        return json_encode(array(
            $this->code,
            $this->balance,
        ));
    }

    public function toArray(){
        return [
            "code" => $this->code,
            "balance" => $this->balance,
        ];
    }

    public function __sleep()
    {
        return ["accountId","code", "balance"];
    }

    public function __wakeup()
    {
        $this->connection = DbConnection::getInstance();
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getAccountId()
    {
        return $this->accountId;
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

    public function addAccount($balance = 0, $userId): bool
    {
        $code = $this->createCode($userId);
        $stmt = $this->connection->prepare("INSERT INTO account (code, balance, user_id) VALUE (:code, :balance, :user_id)");
        $stmt->execute([
            ":code" => $code,
            ":balance" => $balance,
            ":user_id" => $userId
            ]);

        if (!$this->getInfo($userId)) {
            return false;
        }
        return true;
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

    public function getInfo($userId): bool
    {
        $stmt = $this->connection->prepare("
                                            SELECT
                                                code,
                                                balance,
                                                id
                                            FROM
                                                account
                                            WHERE
                                                user_id = :userId
                                            ");

        $stmt->execute([
                        ":userId" => $userId
                        ]);

        $queryResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($queryResults) != 1) {
            return false;
        }

        $info = reset($queryResults);
        $this->code = $info["code"];
        $this->balance = $info["balance"];
        $this->accountId = $info["id"];

        return true;
    }

    public function getInfoByCode($accountCode): bool
    {
        $stmt = $this->connection->prepare("
                                            SELECT                                              
                                                balance,
                                                id
                                            FROM
                                                account
                                            WHERE
                                                code = :accountCode
                                            ");
        $stmt->execute([
                        ":accountCode" => $accountCode
                        ]);
        $queryResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($queryResults) != 1) {
            return false;
        }

        $info = reset($queryResults);
        $this->balance = $info["balance"];
        $this->setCode($accountCode);
        $this->accountId = $info["id"];

        return true;
    }

    public function updateBalance(): bool
    {
        $stmt = $this->connection->prepare(
                                    "UPDATE
                                                account
                                           SET 
                                                balance = :balance
                                           WHERE
                                                code = :code
                                          ");
        if ($stmt->execute([
            ":balance" => $this->balance,
            ":code" => $this->code
        ])){
            return true;
        }
        return false;
    }

    public function deleteAccount(): bool
    {
        $stmt = $this->connection->prepare("
                                            DELETE
                                            FROM
                                                account
                                            WHERE
                                                id = :accountId
                                           ");
        if ($stmt->execute([
            ":userId" => $this->accountId
        ])){
            return true;
        }
        return false;
    }

    private function createCode($userId)
    {
        $data = date("dmy");
        $code = $userId.$data;
        return $code;
    }
}
