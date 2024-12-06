<?php

namespace App\ex10\Service;

class SqlDatabaseService {
    private $connection;
    private $sqlTableName;

    public function __construct(string $dbServer, string $dbUser, string $dbPass, string $dbName) {
        $this->connection = mysqli_connect($dbServer, $dbUser, $dbPass, $dbName);
        $this->sqlTableName = "sql_table_ex10";
        $this->createTable();
    }

    public function __destruct()
    {
        if ($this->connection) {
            mysqli_close($this->connection);
        }
    }

    public function getTableName() {
        return $this->sqlTableName;
    }

    public function createTable()
    {
        $command = "
        CREATE TABLE IF NOT EXISTS $this->sqlTableName (
            id int AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL UNIQUE,
            content LONGTEXT DEFAULT NULL,
            created_at DATETIME NOT NULL
        )";
        try {
            $this->execute($command);
        } catch (\Exception $e) {
            throw new \Exception("Error creating table: $e");
        }
    }

    public function execute(string $sql)
    {
        if (!$this->connection) {
            throw new \Exception('Database connection is not established.');
        }
        $result = mysqli_query($this->connection, $sql);

        if (!$result) {
            throw new \Exception('Execution error: ' . mysqli_error($this->connection));
        }

        return $result;
    }

    public function query(string $sql): ?array
    {
        if (!$this->connection) {
            throw new \Exception('Database connection error: ' . mysqli_connect_error());
        }

        $result = mysqli_query($this->connection, $sql);
        if (!$result) {
            throw new \Exception('Query error: ' . mysqli_error($this->connection));
        }

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

}

?>