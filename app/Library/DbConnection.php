<?php

namespace WjCrypto\Library;

use PDO;

class DbConnection
{
    public function connection()
    {
        $connection = new PDO("mysql:host=localhost;port=3306;dbname=wjcrypto", "vinicius", "webjump");
        return $connection;
    }
}