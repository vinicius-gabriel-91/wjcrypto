<?php
ini_set('display_errors', 'on');

class AccountModel
{
    private $connection;
    private $code;
    private $balance;

    public function __construct()
    {
        $this->connection = new PDO("mysql:host=localhost;port=3306;dbname=wjcrypto", "vinicius", "webjump");
    }

    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    public function addAccount($balance, $userId)
    {
        $code = $this->createCode($userId);
        $stmt = $this->connection->prepare("INSERT INTO account (code, balance, user_id) VALUE (:code, :balance, :user_id)");
        $stmt->execute([
            ":code" => $code,
            ":balance" => $balance,
            ":user_id" => $userId
            ]);
    }

    public function getInfo($userId)
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

        $select = $stmt->fetchAll(PDO::FETCH_ASSOC);
        var_dump($select);
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
                                                code = :code
                                          ");
        $stmt->execute([":balance" => $this->balance, ":code" => $this->code]);
    }

    public function deleteAccount($userId)
    {
        $stmt = $this->connection->prepare("
                                            DELETE
                                            FROM
                                                account
                                            WHERE
                                                user_id = :userId
                                           ");
        $stmt->execute([":userId" => $userId]);
    }

    private function createCode($userId)
    {
        $data = date("dmy");
        $code = $userId.$data;
        return $code;
    }
}
