<?php

namespace App\Service;
use App\Service\TableService;


class AddressService {
    private TableService $tableService;
    private $tableName;

    public function __construct(TableService $tableService) {
        $this->tableName = "addresses";
        $this->tableService = $tableService;
    }

    public function __destruct(){}


    public function createTable()
    {
        $command = "
            CREATE TABLE IF NOT EXISTS $this->tableName (
                id int AUTO_INCREMENT PRIMARY KEY,
                user_id int NOT NULL,
                address TEXT
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

}

?>