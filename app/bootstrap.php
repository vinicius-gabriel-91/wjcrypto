<?php

ini_set('display_errors', 'on');

require_once __DIR__ . '/../vendor/autoload.php';

use Laminas\Diactoros\ServerRequestFactory;
use League\Route\Http\Exception\BadRequestException;
use League\Route\Http\Exception\UnauthorizedException;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use WjCrypto\Library\RouterFactory;
use WjCrypto\Library\AuthManager;
use Laminas\Diactoros\ResponseFactory;

$log = new Logger('name');
$log->pushHandler(new StreamHandler(__DIR__ . '/../var/api.log', Logger::DEBUG));

try {
    $request = ServerRequestFactory::fromGlobals(
        $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
    );

    if ($request->getRequestTarget() !== '/api/user/signup') {
        AuthManager::validateRequest($request);
    }

    $response = RouterFactory::create()->dispatch($request);

    $log->info('Requisição processada com sucesso', [
        'endpoint' => $request->getRequestTarget()
    ]);
} catch (BadRequestException $exception) {
    $log->warning($exception->getMessage(), $exception->getTrace());
    $response = (new ResponseFactory())->createResponse(400, $exception->getMessage());
} catch (UnauthorizedException $exception) {
    $log->warning($exception->getMessage(), $exception->getTrace());
    $response = (new ResponseFactory())->createResponse(401, $exception->getMessage());
} catch (Exception $exception) {
    $log->error($exception->getMessage(), $exception->getTrace());
    $response = (new ResponseFactory())->createResponse(500, $exception->getMessage());
}

// send the response to the browser
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);