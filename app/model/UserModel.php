<?php


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
        $this->connection = new PDO("mysql:host=localhost;port=3306;dbname=wjcrypto", "vinicius", "webjump");
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

    public function getPassword()
    {
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

    public function setSurname($surname)
    {
        $surname = strtoupper($surname);
        $surname = trim($surname);
        $this->surname = $surname;
    }

    public function setBusiness($business)
    {
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

//-----------------------------------------------------------------------

    public function getInfo($email, $password)
    {
        $stmt = $this->connection->prepare("
                                                    SELECT 
                                                        id,
                                                        first_name,
                                                        last_name,
                                                        email, 
                                                        password,
                                                        is_business_customer,
                                                        taxvat,
                                                        person_document_number,
                                                        corporate_name 
                                                    FROM 
                                                        user 
                                                    WHERE
                                                          email =  :email and password = :password");
        $stmt->execute([
            ":email" => $email,
            ":password" => $password
            ]);
        $select = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $selectResult = $select["0"];
        $this->id = $selectResult["id"];
        $this->name = $selectResult["first_name"];
        $this->surname = $selectResult["last_name"];
        $this->email = $selectResult["email"];
        $this->password = $selectResult["password"];
        $this->business = $selectResult["is_business_customer"];
        $this->taxvat = $selectResult["taxvat"];
        $this->documentNumber = $selectResult["person_document_number"];
        $this->corporateName = $selectResult["corporate_name"];


    }

    public function setPassword($password)
    {
        $this->password = $password;
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


