<?php


class CustomerModel
{
    private $connection;

    public function __construct()
    {
        $this->connection = new PDO("mysql:host=localhost;port=3306;dbname=wjcrypto", "vinicius", "webjump");
    }

    public function adduser($name,$surname,$email,$password,$business,$taxvat,$docNumber,$corporateName = null): bool
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO user (first_name, last_name, email, password,is_business_customer, taxvat, person_document_number, corporate_name)
                             VALUES (:first_name, :last_name, :email, :password,:is_business_customer, :taxvat, :person_document_number, :corporate_name)"
        );


        return $stmt->execute([
            ":first_name" => $name,
            ":last_name" => $surname,
            ":email" => $email,
            ":password" => $password,
            ":is_business_customer" => $business,
            ":taxvat" => $taxvat,
            ":person_document_number" => $docNumber,
            ":corporate_name" => $corporateName,
        ]);
    }
}

ini_set('display_errors', 'on');
$dados = ['MAISA', 'ALVES', 'MA565ISA@MAISA', '123456', '0', '12345678910', '123456789', 'NULL'];
$conta = new CustomerModel();
$resultado = $conta->adduser('MAISA', 'ALVES', 'MasdfasSA@MAISA', '123456', 0, '12345678910', '123456789');

var_dump($resultado);


?>


