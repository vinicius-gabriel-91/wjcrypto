<?php

namespace WjCrypto\Library;

use PDO;

class DbConnection
{
    private static $connection = null;

    public static function getInstance()
    {
        if (empty(static::$connection)) {
            static::$connection = new PDO(
                "mysql:host=localhost;port=3306;dbname=wjcrypto",
                "vinicius",
                "webjump"
            );
        }

        return static::$connection;
    }
}