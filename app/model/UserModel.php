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
    private $documentNumber;
    private $corporateName;

    public function __construct()
    {
        $this->connection = new PDO("mysql:host=localhost;port=3306;dbname=wjcrypto", "vinicius", "webjump");
    }

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

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function adduser($name,
                            $surname,
                            $email,
                            $password,
                            $business,
                            $taxvat,
                            $docNumber,
                            $corporateName = null
                            ): bool
    {
        $name = $this->nameTreatment($name);
        $surname = $this->surnameTreatment($surname);
        $corporateName = $this->corporateNameTreatment($corporateName);

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

    private function nameTreatment($name)
    {
        $name = strtoupper($name);
        $name = trim($name);

        return $name;
    }

    private function surnameTreatment($surname)
    {
        $surname = strtoupper($surname);
        $surname = trim($surname);
        return $surname;
    }

    private function corporateNameTreatment($corporateName)
    {
        $corporateName = strtoupper($corporateName);
        $corporateName = trim($corporateName);

        return $corporateName;
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


