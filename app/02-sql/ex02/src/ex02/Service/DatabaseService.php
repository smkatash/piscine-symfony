<?php

namespace App\ex02\Service;


class DatabaseService {
    private $connection;

    public function __construct(string $dbServer, string $dbUser, string $dbPass, string $dbName) {
        $this->connection = mysqli_connect($dbServer, $dbUser, $dbPass, $dbName);
    }

    public function __destruct()
    {
        if ($this->connection) {
            mysqli_close($this->connection);
        }
    }

    public function execute(string $sql)
    {
        if (!$this->connection) {
            throw new Exception('Database connection is not established.');
        }
        $result = mysqli_query($this->connection, $sql);

        if (!$result) {
            throw new Exception('Execution error: ' . mysqli_error($this->connection));
        }

        return $result;
    }

    public function query(string $sql): ?array
    {
        if (!$this->connection) {
            throw new Exception('Database connection error: ' . mysqli_connect_error());
        }

        $result = mysqli_query($this->connection, $sql);
        if (!$result) {
            throw new Exception('Query error: ' . mysqli_error($this->connection));
        }

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

}

?>