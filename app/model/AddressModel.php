<?php

class AddressModel
{
    private $connection;
    private $userId;
    private $addressId;
    private $street;
    private $streetNumber;
    private $streetNumberAdition;
    private $postalCode;
    private $city;
    private $country;
    private $phoneNumber;

    public function __construct()
    {
        $connection = new DbConnection();
        $this->connection = $connection->connection();
    }

    public function __wakeup()
    {
        $connection = new DbConnection();
        $this->connection = $connection->connection();
    }

    public function __sleep()
    {
        return [
            "addressId",
            "street",
            "streetNumber",
            "streetNumberAdition",
            "postalCode",
            "city",
            "country",
            "phoneNumber",
        ];
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getAddressId()
    {
        return $this->addressId;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    public function getStreetNumberAdition()
    {
        return $this->streetNumberAdition;
    }

    public function getPostalCode()
    {
        return $this->postalCode;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setStreet($street)
    {
        $street = strtoupper($street);
        $street = trim($street);
        $this->street = $street;
    }

    public function setStreetNumber($streetNumber)
    {
        $streetNumber = strtoupper($streetNumber);
        $streetNumber = trim($streetNumber);
        $this->streetNumber = $streetNumber;
    }

    public function setStreetNumberAdition($streetNumberAdition)
    {
        $streetNumberAdition = strtoupper($streetNumberAdition);
        $streetNumberAdition = trim($streetNumberAdition);
        $this->streetNumberAdition = $streetNumberAdition;
    }

    public function setPostalCode($postalCode)
    {
        $postalCode = trim($postalCode);
        $this->postalCode = $postalCode;
    }

    public function setCity($city)
    {
        $city = strtoupper($city);
        $city = trim($city);
        $this->city = $city;
    }

    public function setCountry($country)
    {
        $country = strtoupper($country);
        $country = trim($country);
        $this->country = $country;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $phoneNumber = trim($phoneNumber);
        $this->phoneNumber = $phoneNumber;
    }

    public function addAddress()
    {
        $stmt = $this->connection->prepare(
                                    "INSERT INTO
                                            address
                                                (
                                                user_id,
                                                street,
                                                street_number,
                                                street_number_adition,
                                                postal_code,
                                                city,
                                                country,
                                                phone_number
                                                )
                                            VALUES 
                                                (
                                                :user_id,
                                                :street,
                                                :street_number,
                                                :street_number_adition,
                                                :postal_code,
                                                :city,
                                                :country,
                                                :phone_number
                                                )                                                                               
                                            ");
        $stmt->execute([
                        ":user_id" => $this->userId,
                        ":street" => $this->street,
                        ":street_number" => $this->streetNumber,
                        ":street_number_adition" => $this->streetNumberAdition,
                        ":postal_code" => $this->postalCode,
                        ":city" => $this->city,
                        ":country" => $this->country,
                        ":phone_number"  => $this->phoneNumber
        ]);
    }

    public function getList():array
    {
        $stmt = $this->connection->prepare("
                                            SELECT
                                                id,
                                                street,
                                                street_number,
                                                street_number_adition,
                                                postal_code,
                                                city,
                                                country,
                                                phone_number
                                            FROM
                                                address
                                            WHERE
                                                user_id = :userId
                                            ");
        $stmt->execute([
                        ":userId" => $this->userId,
                        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateAddress($userId)
    {
        $stmt = $this->connection->prepare("
                                            UPDATE
                                                address
                                            SET
                                                street = :street,
                                                street_number = :streetNumber,
                                                street_number_adition = :streetNumberAdition,
                                                postal_code = :postalCode,
                                                city = :city,
                                                country = :country,
                                                phone_number = :phoneNumber
                                            WHERE
                                                user_id = :userId
                                            ");
        $stmt->execute([
                        ":street" => $this->street,
                        ":streetNumber" => $this->streetNumber,
                        ":streetNumberAdition" => $this->streetNumberAdition,
                        ":postalCode" => $this->postalCode,
                        ":city" => $this->city,
                        ":phoneNumber" => $this->phoneNumber,
                        ":country" => $this->country,
                        ":userId" => $userId
                       ]);
    }

    public function getInfo($userId)
    {
        $stmt = $this->connection->prepare("
                                            SELECT
                                                id,
                                                street,
                                                street_number,
                                                street_number_adition,
                                                postal_code,
                                                city,
                                                country,
                                                phone_number
                                            FROM
                                                address
                                            WHERE
                                                user_id = :userId;
                                           ");
        $stmt->execute([
                        ":userId" => $userId
                        ]);
        $select = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $selectResult = $select["0"];
        $this->addressId = $selectResult["id"];
        $this->street = $selectResult["street"];
        $this->streetNumber = $selectResult["street_number"];
        $this->streetNumberAdition = $selectResult["street_number_adition"];
        $this->postalCode = $selectResult["postal_code"];
        $this->city = $selectResult["city"];
        $this->country = $selectResult["country"];
        $this->phoneNumber = $selectResult["phone_number"];

    }

    public function deleteAddress()
    {
        $stmt = $this->connection->prepare("
                                            DELETE
                                            FROM
                                                address
                                            WHERE
                                                id = :addressId
                                           ");
        $stmt->execute([":addressId" => $this->addressId]);
    }
}

