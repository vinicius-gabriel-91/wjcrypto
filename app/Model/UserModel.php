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

    public function __construct()
    {
        $this->connection = DbConnection::getInstance();
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
        $this->connection = DbConnection::getInstance();
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

    public function getPassword(): string   {
        return $this->password;
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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'business' => $this->business,
            'taxvat' => $this->taxvat,
            'docNumber' => $this->docNumber,
            'corporateName' => $this->corporateName,
        ];
    }

//-----------------------------------------------------------------------

    public function fetchInfo(string $email): bool
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
                                                        password,
                                                        corporate_name 
                                                    FROM 
                                                        user 
                                                    WHERE
                                                          email =  :email
                                               ");

        $stmt->execute([
            ":email" => $email,
        ]);

        $queryResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($queryResults) != 1) {
            return false;
        }

        $info = reset($queryResults);

        $this->id = $info["id"];
        $this->name = $info["first_name"];
        $this->surname = $info["last_name"];
        $this->email = $info["email"];
        $this->password = $info["password"];
        $this->business = $info["is_business_customer"];
        $this->taxvat = $info["taxvat"];
        $this->docNumber = $info["person_document_number"];
        $this->corporateName = $info["corporate_name"];

        return true;
    }

    public function adduser(): bool
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

        if (!$this->fetchInfo($this->email)) {
            return false;
        }
        return true;
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

    public function verifyIfUserExists($email): bool{
        $stmt = $this->connection->prepare("
        SELECT
            email
        FROM
            user
        WHERE
            email = :email
        ");

        $stmt->execute([":email" => $email]);

        $queryResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($queryResults) >= 1) {
            return true;
        }

        return false;

    }
}



