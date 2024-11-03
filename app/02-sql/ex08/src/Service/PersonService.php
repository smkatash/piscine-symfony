<?php

namespace App\Service;
use App\Service\TableService;

class PersonService {
    private TableService $tableService;
    private $tableName;

    public function __construct(TableService $tableService) {
        $this->tableName = "persons";
        $this->tableService = $tableService;
    }

    public function __destruct(){}


    public function createTable()
    {
        $command = "
            CREATE TABLE IF NOT EXISTS $this->tableName (
                id int AUTO_INCREMENT PRIMARY KEY,
                username varchar(50) NOT NULL UNIQUE,
                name varchar(255) NOT NULL,
                email varchar(255) NOT NULL UNIQUE,
                enable BOOLEAN,
                birthdate DATETIME
            )";
        return $this->tableService->createTable($command);
    }

    public function dropTable(string $dbName)
    {
        return $this->tableService->dropTableIfExists($dbName);
    }

    public function addColumn(string $columnName, string $dataType, bool $isNullable)
    {
        return $this->tableService->addColumn($columnName, $dataType, $isNullable);
    }

    public function getTable(string $dbName) {
        return $this->tableService->getTable($dbName);
    }

    public function addRelationship(bool $oneToOne = true) {
        $relationshipType = $oneToOne ? 'one-to-one' : 'one-to-many';
        return $this->tableService->addRelationship(
            $this->tableName,
            'bank_accounts',
            $relationshipType,
            'bank_account_id',
            'id',
            true
        );
    }

}

?>