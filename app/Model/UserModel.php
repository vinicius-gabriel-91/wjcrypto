<?php

namespace WjCrypto\Model;

use PDO;
use WjCrypto\Library\DbConnection;

class UserModel
{
    private $connection;
    private $id;
    private $name;
    private $surname;
    private $email;
    private $password;
    private $business;
    private $taxvat;
    private $docNumber;
    private $corporateName;

    public function __construct(DbConnection $connection)
    {
        $this->connection = $connection->connection();
    }

    public function __sleep():array
    {
        return [
            "id",
            "name",
            "surname",
            "email",
            "password",
            "business",
            "taxvat",
            "docNumber",
            "corporateName"
        ];
    }

    public function __wakeup()
    {
        $connection = new DbConnection();
        $this->connection = $connection->connection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword($email)
    {
        $stmt = $this->connection->prepare("
            SELECT
                password
            FROM
                user
            WHERE
                email = :email
        ");
        $stmt->execute([":email" => $email]);
        $select = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $password = $select["0"];
        return $password["password"];

    }

    public function getBusiness()
    {
        return $this->business;
    }

    public function getTaxvat()
    {
        return $this->taxvat;
    }

    public function getDocumentNumber()
    {
        return $this->docNumber;
    }

    public function getCorporateName()
    {
        return $this->corporateName;
    }

    public function setName($name)
    {
        $name = strtoupper($name);
        $name = trim($name);
        $this->name = $name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setSurname($surname)
    {
        $surname = strtoupper($surname);
        $surname = trim($surname);
        $this->surname = $surname;
    }

    public function setBusiness($business)
    {
        $business = intval($business);
        $this->business = $business;
    }

    public function setTaxvat($taxvat)
    {
        $this->taxvat = $taxvat;
    }

    public function setDocNumber($docNumber)
    {
        $this->docNumber = $docNumber;
    }

    public function setCorporateName($corporateName)
    {
        $corporateName = strtoupper($corporateName);
        $corporateName = trim($corporateName);
        $this->corporateName = $corporateName;
    }

    public function setId($id)
    {
        $this->id = $id;
    }



//-----------------------------------------------------------------------

    public function getInfo()
    {
        $stmt = $this->connection->prepare("
                                                    SELECT 
                                                        id,
                                                        first_name,
                                                        last_name,
                                                        email, 
                                                        is_business_customer,
                                                        taxvat,
                                                        person_document_number,
                                                        corporate_name 
                                                    FROM 
                                                        user 
                                                    WHERE
                                                          email =  :email
                                               ");
        $stmt->execute([
            ":email" => $this->email,
            ]);
        $select = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $selectResult = $select["0"];
        $this->id = $selectResult["id"];
        $this->name = $selectResult["first_name"];
        $this->surname = $selectResult["last_name"];
        $this->email = $selectResult["email"];
        $this->business = $selectResult["is_business_customer"];
        $this->taxvat = $selectResult["taxvat"];
        $this->docNumber = $selectResult["person_document_number"];
        $this->corporateName = $selectResult["corporate_name"];
    }

    public function adduser()
    {

        $stmt = $this->connection->prepare(
                                    "INSERT INTO
                                                 user
                                                 (first_name,
                                                  last_name,
                                                  email,
                                                  password,
                                                  is_business_customer,
                                                  taxvat,
                                                  person_document_number,
                                                  corporate_name)
                                            VALUES
                                                  (:first_name,
                                                   :last_name,
                                                   :email,
                                                   :password,
                                                   :is_business_customer,
                                                   :taxvat,
                                                   :person_document_number,
                                                   :corporate_name)"
                                        );


        $stmt->execute([
            ":first_name" => $this->name,
            ":last_name" => $this->surname,
            ":email" => $this->email,
            ":password" => $this->password,
            ":is_business_customer" => $this->business,
            ":taxvat" => $this->taxvat,
            ":person_document_number" => $this->docNumber,
            ":corporate_name" => $this->corporateName,
        ]);
    }

    public function updateUser()
    {
       $stmt = $this->connection->prepare("
                                                 UPDATE
                                                    user
                                                 SET
                                                    email = :email,
                                                    password = :password
                                                 WHERE
                                                    id = :id
                                         ");
        $stmt->execute([
                        ":email" => $this->email,
                        ":password" => $this->password,
                        ":id" => $this->id

        ]);

    }

    public function deleteUser($userId)
    {
        $stmt = $this->connection->prepare("
                                            DELETE
                                            FROM
                                                user
                                            WHERE
                                                id = :userId;
                                            ");
        $stmt->execute([":userId" => $userId]);
    }
}
?>


