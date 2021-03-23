<?php

namespace WjCrypto\Model;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;
use WjCrypto\Library\AuthManager;
use WjCrypto\Library\DbConnection;

class LogModel
{
    public static function create(
        ServerRequestInterface $request,
        string $message,
        ?int $accountId = null
    ): void {
        $args = [
            ':userId' => AuthManager::getLoggedUser()->getId(),
            ':accountId' => $accountId,
            ':path' => $request->getRequestTarget(),
            ':device' => $request->getServerParams()['HTTP_USER_AGENT'],
            ':message' => $message
        ];

        $stmt = DbConnection::getInstance()->prepare(
           "INSERT INTO application_log(
                user_id,
                account_id,
                path,
                device,
                message                                
            ) VALUES (
               :userId,
               :accountId,
               :path,
               :device,
               :message                   
            );"
        );

        if (!$stmt->execute($args)) {
            $log = new Logger('name');
            $log->pushHandler(new StreamHandler(__DIR__ . '/../../var/api.log', Logger::DEBUG));
            $log->warning('failed to log activity', $stmt->errorInfo());
            $log->info('inserted values', $args);
        }
    }

    public function setMessage($request)
    {

        $message = "";

        if ($request->getRequestTarget() == "/api/user/login"){
            $message = "login realizado";
        } elseif ($request->getRequestTarget() == "/api/user/info"){
            $message = "Acesso ao profile";
        } elseif ($request->getRequestTarget() == "/api/account/updateaddress"){
            $message = "Atualização de endereço";
        } elseif ($request->getRequestTarget() == "/api/user/signout") {
            $message = "Logout realizado";
        } elseif ($request->getRequestTarget() == "/api/transaction/Deposito") {
            $message = "Deposito realizado";
        } elseif ($request->getRequestTarget() == "/api/transaction/Saque") {
            $message = "Saque realizado";
        } elseif ($request->getRequestTarget() == "/api/transaction/Transferencia") {
            $message = "Transferencia realizada";
        }
        $this->message = $message;
    }
}