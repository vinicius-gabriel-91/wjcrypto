<?php


class TransactionModel
{
    private $typeId;
    private $accountId;
    private $targetId = null;
    private $value;

    public function __construct()
    {
        $connection = new DbConnection();
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
            FROM account_transaction at
            inner join transaction_type tt
            on at.transaction_type_id = tt.id
            inner join account ac
            on at.target_account_id = ac.id
            where
            origin_account_id = :accountId;
            ");
        $stmt->execute([
                        ":accountId" => $this->accountId
        ]);
        $select = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $select;
    }
}
