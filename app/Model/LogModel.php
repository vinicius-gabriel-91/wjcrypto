<?php

namespace WjCrypto\Model;

use WjCrypto\Library\DbConnection;

class LogModel
{
    private $connection;
    private $accountId;
    private $sessionId;
    private $device;
    private $message;
    private $params;

    public function __construct()
    {
        $this->connection = DbConnection::getInstance();
        $this->setMessage();
        $this->setSessionId();
        $this->setDevice();
    }

    public function setAccountId()
    {
        if ($_SESSION["account"]) {
            $account = unserialize($_SESSION["account"]);
            $this->accountId = $account->getAccountId();
        } else {
            $this->accountId = null;
        }
    }

    public function setSessionId()
    {
        $this->sessionId = session_id();
    }

    public function setDevice()
    {
        $this->device = $_SERVER["HTTP_USER_AGENT"];
    }

    public function setMessage()
    {
        $message = "";

        if ($this->params["action"] == "user"){
            $message = "user realizado";
        } elseif ($this->params["action"] == "profile"){
            $message = "Acesso ao profile";
        } elseif ($this->params["action"] == "updateAddress"){
            $message = "Atualização de endereço";
        } elseif ($this->params["action"] == "logout") {
            $message = "Logout realizado";
        } elseif ($this->params["action"] == "Deposito") {
            $message = "Deposito realizado";
        } elseif ($this->params["action"] == "Saque") {
            $message = "Saque realizado";
        } elseif ($this->params["action"] == "Transferencia") {
            $message = "Transferencia realizada";
        }
        $this->message = $message;
    }

    public function logActivity($userId)
    {
        $this->setAccountId();
        $stmt = $this->connection->prepare("
           INSERT INTO
                application_log(
                                user_id,
                                account_id,
                                session_identifier,
                                device,
                                message                                
                )
            VALUES(
                   :userId,
                   :accountId,
                   :sessionId,
                   :device,
                   :message                   
            ) 
            ");

        $stmt->execute([
            ":userId" => $userId,
            ":accountId" => $this->accountId,
            ":sessionId" => $this->sessionId,
            ":device" => $this->device,
            ":message" => $this->message
        ]);
    }
}