<?php

namespace App\Service;
use App\Service\DatabaseService;


class TableService {
    private DatabaseService $db;
    private string $tableName;

    public function __construct(DatabaseService $db, string $tableName) {
        $this->db = $db;
        $this->tableName = $tableName;
    }

    public function __destruct(){}


    public function createTable(string $command)
    {
        return $this->db->execute($command);
    }

    public function dropTableIfExists(string $dbName)
    {
        $this->dropForeignKeys($dbName);
        $command = "
        DROP TABLE IF EXISTS $this->tableName;
        ";
        return $this->db->execute($command);
    }

    public function addColumn(string $columnName, string $dataType, bool $isNullable)
    {
        $null = $isNullable ? "NULL" : "NOT NULL";
        $command = "
        ALTER TABLE $this->tableName 
        ADD $columnName $dataType $null;
        ";

        return $this->db->execute($command);
    }

    public function getTable(string $dbName) {
        $command = "
            SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT, CHARACTER_MAXIMUM_LENGTH
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = '$dbName'
            AND TABLE_NAME = '$this->tableName';
        ";
        $columns = $this->db->query($command);
        
        $relationshipsCommand = "
            SELECT
                kcu.COLUMN_NAME AS foreign_column,
                tc.TABLE_NAME AS referenced_table,
                kcu.ORDINAL_POSITION AS position
            FROM
                information_schema.KEY_COLUMN_USAGE AS kcu
            JOIN
                information_schema.TABLE_CONSTRAINTS AS tc ON kcu.CONSTRAINT_NAME = tc.CONSTRAINT_NAME
            WHERE
                kcu.TABLE_SCHEMA = '$dbName'
                AND kcu.TABLE_NAME = '$this->tableName'
                AND tc.CONSTRAINT_TYPE = 'FOREIGN KEY';
        ";
        $relationships = $this->db->query($relationshipsCommand);

        return [
            'columns' => $columns,
            'relationships' => $relationships
        ];
    }

    public function addRelationship(
        string $tableName,
        string $targetTable,
        string $relationshipType,
        string $columnName,
        string $targetColumn = "id",
        bool $onDeleteCascade = false
    ) {
        $onDelete = $onDeleteCascade ? "ON DELETE CASCADE" : "";
        
        switch ($relationshipType) {
            case 'one-to-one':
                $command = "
                    ALTER TABLE $tableName
                    ADD COLUMN $columnName INT UNIQUE,
                    ADD CONSTRAINT fk_{$tableName}_{$targetTable}
                    FOREIGN KEY ($columnName) REFERENCES $targetTable($targetColumn)
                    $onDelete
                ";
                break;
            
            case 'one-to-many':
                $command = "
                    ALTER TABLE $tableName
                    ADD COLUMN $columnName INT,
                    ADD CONSTRAINT fk_{$tableName}_{$targetTable}
                    FOREIGN KEY ($columnName) REFERENCES $targetTable($targetColumn)
                    $onDelete
                ";
                break;

            default:
                throw new \InvalidArgumentException("Unsupported relationship type: $relationshipType");
        }

        return $this->db->execute($command);
    }

    private function dropForeignKeys(string $dbName) {
        $query = "
            SELECT 
                CONSTRAINT_NAME,
                TABLE_NAME
            FROM 
                information_schema.KEY_COLUMN_USAGE
            WHERE 
                TABLE_SCHEMA = '$dbName' 
                AND REFERENCED_TABLE_NAME = '$this->tableName';
        ";
    
        $referencingFKeys = $this->db->query($query);
    
        if (!empty($referencingFKeys)) {
            foreach ($referencingFKeys as $fk) {
                $foreignKeyName = $fk['CONSTRAINT_NAME'];
                $referencingTable = $fk['TABLE_NAME'];
                $dropCommand = "ALTER TABLE `$referencingTable` DROP FOREIGN KEY `$foreignKeyName`;";
                $this->db->execute($dropCommand);
            }
        }
    
        $query = "
            SELECT 
                CONSTRAINT_NAME
            FROM 
                information_schema.KEY_COLUMN_USAGE
            WHERE 
                TABLE_SCHEMA = '$dbName' 
                AND TABLE_NAME = '$this->tableName'
                AND REFERENCED_TABLE_NAME IS NOT NULL;
        ";
    
        $foreignKeys = $this->db->query($query);
        if (!empty($foreignKeys)) {
            foreach ($foreignKeys as $fk) {
                $foreignKeyName = $fk['CONSTRAINT_NAME'];
                $dropCommand = "ALTER TABLE `$this->tableName` DROP FOREIGN KEY `$foreignKeyName`;";
                $this->db->execute($dropCommand);
            }
        }
    }
}

?>