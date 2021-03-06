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

    public function __construct(DbConnection $connection)
    {
        $this->connection = $connection->connection();
    }

    public function getAccountId()
    {
        return $this->accountId;
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

    public function addTransaction()
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
        $stmt->execute([
                        ":typeId" => $this->typeId,
                        ":accountId" => $this->accountId,
                        ":targetId" => $this->targetId,
                        ":value" => $this->value
        ]);
    }

    public function getTypeId($description)
    {
        $stmt = $this->connection->prepare("SELECT
                                                        *
                                                    FROM 
                                                        transaction_type
                                                    WHERE 
                                                        description = :description;");
        $stmt->execute([":description" => $description]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $value = $result["0"];
        $this->typeId = $value["id"];
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
            ");

        $stmt->execute([":accountId" => $this->accountId]);
        $select = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $select;
    }
}

