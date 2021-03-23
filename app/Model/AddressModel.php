<?php

namespace WjCrypto\Model;

use PDO;
use WjCrypto\Library\DbConnection;

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
        $this->connection = DbConnection::getInstance();
    }

    public function __wakeup()
    {
        $this->connection = DbConnection::getInstance();
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

    public function addAddress(): bool
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

        if (!$stmt->execute([
                        ":user_id" => $this->userId,
                        ":street" => $this->street,
                        ":street_number" => $this->streetNumber,
                        ":street_number_adition" => $this->streetNumberAdition,
                        ":postal_code" => $this->postalCode,
                        ":city" => $this->city,
                        ":country" => $this->country,
                        ":phone_number"  => $this->phoneNumber
        ])){
            return false;
        }

        return true;

    }

    public function toArray(): array
    {
        return [
            'userId' => $this->userId,
            'addressId' => $this->addressId,
            'street' => $this->street,
            'streetNumber' => $this->streetNumber,
            'streetNumberAdition' => $this->streetNumberAdition,
            'postalCode' => $this->postalCode,
            'city' => $this->city,
            'country' => $this->country,
            'phoneNumber' => $this->phoneNumber,
        ];
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

    public function updateAddress($userId): bool
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
        if (!$stmt->execute([
                        ":street" => $this->street,
                        ":streetNumber" => $this->streetNumber,
                        ":streetNumberAdition" => $this->streetNumberAdition,
                        ":postalCode" => $this->postalCode,
                        ":city" => $this->city,
                        ":phoneNumber" => $this->phoneNumber,
                        ":country" => $this->country,
                        ":userId" => $userId
                       ])){
            return false;
        }
        return true;
    }

    public function getInfo($userId):bool
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

        $queryResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($queryResults) != 1) {
            return false;
        }

        $info = reset($queryResults);

        $this->addressId = $info["id"];
        $this->street = $info["street"];
        $this->streetNumber = $info["street_number"];
        $this->streetNumberAdition = $info["street_number_adition"];
        $this->postalCode = $info["postal_code"];
        $this->city = $info["city"];
        $this->country = $info["country"];
        $this->phoneNumber = $info["phone_number"];

        return true;
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

