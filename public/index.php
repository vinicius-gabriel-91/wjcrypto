<?php

////var_dump(php_ini_loaded_file(), php_ini_scanned_files());
require_once __DIR__ . "/../app/bootstrap.php";

$connection = new \WjCrypto\Library\DbConnection();
$logActivity = new \WjCrypto\Model\LogModel($connection);
$accountController = new \WjCrypto\Controller\AccountController($logActivity, $connection);
$transactionController = new \WjCrypto\Controller\TransactionController($logActivity,$connection);