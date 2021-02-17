<?php


class AddressModel
{
    private $connection;
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
        $this->connection = new PDO("mysql:host=localhost;port=3306;dbname=wjcrypto", "vinicius", "webjump");
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

    public function addAddress($userId)
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
                        ":user_id" => $userId,
                        ":street" => $this->street,
                        ":street_number" => $this->streetNumber,
                        ":street_number_adition" => $this->streetNumberAdition,
                        ":postal_code" => $this->postalCode,
                        ":city" => $this->city,
                        ":country" => $this->country,
                        ":phone_number"  => $this->phoneNumber
        ]);
    }

    public function getList($userId):array
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
                        ":userId" => $userId,
                        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateAddress()
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
                                                id = :addressId
                                            ");
        $stmt->execute([
                        ":street" => $this->street,
                        ":streetNumber" => $this->streetNumber,
                        ":streetNumberAdition" => $this->streetNumberAdition,
                        ":postalCode" => $this->postalCode,
                        ":city" => $this->city,
                        ":phoneNumber" => $this->phoneNumber,
                        ":country" => $this->country,
                        ":addressId" => $this->addressId
                       ]);
    }

    public function getInfo($addressId)
    {
        $stmt = $this->connection->prepare("
                                            SELECT
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
                                                id = :addressId;
                                           ");
        $stmt->execute([
                        ":addressId" => $addressId
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
        $this->addressId = $addressId;

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

