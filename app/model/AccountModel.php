<?php
require_once 'UserModel.php';

class AccountModel
{
    private $connection;

    public function __construct()
    {
        $this->connection = new PDO("mysql:host=localhost;port=3306;dbname=wjcrypto", "vinicius", "webjump");
    }

    public function addAccount($code, $balance, $userId)
    {
        $stmt = $this->connection->prepare("INSERT INTO account (code, balance, user_id) VALUE (:code, :balance, :user_id)");
        $stmt->execute([
            ":code" => $code,
            ":balance" => $balance,
            ":user_id" => $userId
            ]);
    }
}
ini_set('display_errors', 'on');
$conta = new AccountModel();
$resultado = $conta->addAccount("123456",100,10);

var_dump($resultado);