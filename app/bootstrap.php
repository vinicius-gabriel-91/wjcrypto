<?php

session_start();
ini_set('display_errors', 'on');

define("KEY_SESSION_LOGGED_USER", "logedUser");

require_once __DIR__ . '/../vendor/autoload.php';

use Laminas\Diactoros\ServerRequestFactory;
use WjCrypto\Library\RouterFactory;

try {
    $request = ServerRequestFactory::fromGlobals(
        $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
    );

    $response = RouterFactory::create()->dispatch($request);

    // send the response to the browser
    (new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);
} catch (Exception $exception) {
    echo "<pre>";
    print_r($exception);
}
