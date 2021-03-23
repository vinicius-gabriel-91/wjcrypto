<?php

namespace WjCrypto\Model;

use PDO;
use WjCrypto\Library\DbConnection;

class TransactionModel
{
    private $typeId;
    private $accountId;
    private $targetId = null;
    private $value;

    public function __construct()
    {
        $this->connection = DbConnection::getInstance();
    }

    public function getAccountId()
    {
        return $this->accountId;
    }

    public function setTypeId($typeId)
    {
        if ($typeId == 'Deposito'){
            $id = 1;
        } elseif ($typeId == 'Saque'){
            $id = 2;
        } else {
            $id = 3;
        }
        $this->typeId = $id;
    }

    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
    }

    public function getTargetId()
    {
        return $this->targetId;
    }

    public function setTargetId($targetId)
    {
        $this->targetId = $targetId;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
//____________________________________________________________________________

    public function addTransaction(): bool
    {
        $stmt = $this->connection->prepare("
                                           INSERT
                                           INTO
                                                account_transaction(
                                                                    transaction_type_id,
                                                                    origin_account_id,
                                                                    target_account_id,
                                                                    value 
                                                )   
                                           VALUES(
                                                  :typeId,
                                                  :accountId,
                                                  :targetId,
                                                  :value 
                                           )
                                           ");
        if (!$stmt->execute([
                        ":typeId" => $this->typeId,
                        ":accountId" => $this->accountId,
                        ":targetId" => $this->targetId,
                        ":value" => $this->value
        ])){
            return false;
        }
        return true;
    }

    public function getTypeId($description): bool
    {
        $stmt = $this->connection->prepare("SELECT
                                                        *
                                                    FROM 
                                                        transaction_type
                                                    WHERE 
                                                        description = :description;");
        $stmt->execute([":description" => $description]);
        $queryResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($queryResults) != 1) {
            return false;
        }

        $info = reset($queryResults);
        $this->typeId = $info["id"];
        return true;
    }

    public function getList() :array
    {
        $stmt = $this->connection->prepare("
            SELECT
                date_time,
                value,
                code,
                description
            FROM 
                 account_transaction at
            LEFT JOIN 
                transaction_type tt
            ON 
                at.transaction_type_id = tt.id
            LEFT JOIN 
                account ac
            ON 
                at.target_account_id = ac.id
            WHERE
                origin_account_id = :accountId 
            ORDER BY    
                date_time
            ");

        $stmt->execute([":accountId" => $this->accountId]);
        $select = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $select;
    }
}

