<?php
ini_set('display_errors', 'on');

class TransactionModel
{

    public function __construct()
    {
        $this->connection = new PDO("mysql:host=localhost;port=3306;dbname=wjcrypto", "vinicius", "webjump");
    }

    public function getInfo($id)
    {
        $stmt = $this->connection->prepare("SELECT
                                                        * 
                                                    FROM 
                                                         transaction_type 
                                                    WHERE 
                                                          id = :id;");
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $value = $result["0"];
        $description = $value["description"];
        return $description;
    }
}

$test = new TransactionModel();
$result = $test->getInfo(1);
var_dump($result);

?>