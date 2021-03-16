<?php

namespace WjCrypto\Library;

use Laminas\Diactoros\ResponseFactory;
use League\Route\Strategy\StrategyAwareInterface;
use League\Route\Strategy\JsonStrategy;
use League\Route\Router;
use WjCrypto\Controller\AccountController;
use WjCrypto\Controller\TransactionController;
use WjCrypto\Controller\UserController;

final class RouterFactory
{
    public static function create(): StrategyAwareInterface
    {
        $responseFactory = new ResponseFactory();

        $strategy = new JsonStrategy($responseFactory);
        $router   = (new Router)->setStrategy($strategy);

        $router->map('POST', '/api/user/login', [UserController::class, 'login']);
        $router->map('POST', '/api/user/signup', [UserController::class, 'newUser']);
        $router->map('POST', '/api/user/signout', [UserController::class, 'logout']);
        $router->map('GET', '/api/user/info', [UserController::class, 'fetchLoggedUserInfo']);
        $router->map('POST', '/api/user/updateaddress', [AccountController::class, 'updateAddress']);
        $router->map('GET', '/api/user/accountinfo', [AccountController::class, 'getAccount']);
        $router->map('POST', '/api/user/deposit', [TransactionController::class, 'deposit']);
        $router->map('POST', '/api/user/withdraw', [TransactionController::class, 'withdraw']);
        $router->map('POST', '/api/user/transfer', [TransactionController::class, 'transfer']);
        $router->map('GET', '/api/user/transactionlist', [TransactionController::class, 'getTransactionList']);

        return $router;
    }
}
