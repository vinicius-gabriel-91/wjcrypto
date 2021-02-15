<?php


class AddressModel
{
    private $connection;
    private $street;
    private $streetNumber;
    private $streetNumberAdition;
    private $postalCode;
    private $city;
    private $country;
    private $phoneNumber;
//------------------- CONSTRUCT / GETTERS / SETTERS---------------------------
    public function __construct()
    {
        $this->connection = new PDO("mysql:host=localhost;port=3306;dbname=wjcrypto", "vinicius", "webjump");
    }
    public function getStreet()
    {
        return $this->street;
    }
    public function setStreet($street)
    {
        $this->street = $street;
    }
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;
    }
    public function getStreetNumberAdition()
    {
        return $this->streetNumberAdition;
    }
    public function setStreetNumberAdition($streetNumberAdition)
    {
        $this->streetNumberAdition = $streetNumberAdition;
    }
    public function getPostalCode()
    {
        return $this->postalCode;
    }
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }
    public function getCity()
    {
        return $this->city;
    }
    public function setCity($city)
    {
        $this->city = $city;
    }
    public function getCountry()
    {
        return $this->country;
    }
    public function setCountry($country)
    {
        $this->country = $country;
    }
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function addAddress($userId,
                                $street,
                                $streetNumber,
                                $adition,
                                $postalCode,
                                $city,
                                $country,
                                $phoneNumber
    )
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
                        ":street" => $street,
                        ":street_number" => $streetNumber,
                        ":street_number_adition" => $adition,
                        ":postal_code" => $postalCode,
                        ":city" => $city,
                        ":country" => $country,
                        ":phone_number"  => $phoneNumber
        ]);
    }

}
