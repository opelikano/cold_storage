<?php

class Db
{
    private $pdo;

    public function __construct($connName)
    {
        $conn = $this->getConnection($connName);
 
        try {
            $dsn = "mysql:host={$conn['host']};dbname={$conn['dbname']};charset=utf8";
            $this->pdo = new PDO($dsn, $conn['username'], $conn['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection($name) 
    {
        $conn = [];
        $conn['local']['host'] = 'localhost';
        $conn['local']['dbname'] = 'cold_storage';
        $conn['local']['username'] = 'phpmyadmin';
        $conn['local']['password'] = 'root';

        return $conn[$name];
    }

    public function select($sql, $params = [], $flag = '')
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            if ($flag == 'one') {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            echo "Query error: " . $e->getMessage();
        }
    }

    public function execute($sql, $params = [])
    {
        interpolateQuery($sql, $params);
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            echo "Execution error: " . $e->getMessage();
            return false;
        }
    }

    public function lastInsertId()
    {
        try {
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            echo "Error getting last insert ID: " . $e->getMessage();
            return false;
        }
    }

    public function insert(string $table, array $params) 
    {
        $keys = implode(', ', array_keys($params));
        $keys2 = implode(', ', array_map(fn($key) => ':' . $key, array_keys($params)));

        $sql = "INSERT INTO `{$table}` ({$keys}) VALUES ({$keys2})";
        try {
            // interpolateQuery($sql, $params);
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            echo "Execution error: " . $e->getMessage();
            return false;
        }
    }
}
