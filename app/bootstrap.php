<?php
//    use Monolog\Logger;
//    use Monolog\Handler\StreamHandler;

try {

    ini_set('display_errors', 'On');
    session_start();


    include_once __DIR__ . '/../vendor/autoload.php';

} catch (Exception $ex){

    echo json_encode(array(
        "message"=>$ex->getMessage(),
        "code"=>$ex->getCode()
    ));
}

//    $log = new Logger('wjcrypto');
//    $log->pushHandler(new StreamHandler(__DIR__ . '/etc/wjcrypto.log', Logger::WARNING));

// add records to the log
//$log->warning('Foo');
//$log->error('Bar');