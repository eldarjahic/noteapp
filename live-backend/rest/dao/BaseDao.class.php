<?php
require_once __DIR__ ."/../config.php";

class BaseDao{
    protected $connection;  
    protected $table;

    public function __construct($table){
        $this->table = $table;

        try {
            $this->connection = new PDO("mysql:dbname=".DB_NAME.";host=".DB_HOST.";port=".DB_PORT, DB_USER, DB_PASSWORD,[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            
            ]);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw $e;
        }
    }
    protected function query($query, $params) {
        $statement = $this->connection->prepare($query);
        $statement->execute($params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function query_unique($query, $params) {
        $results = $this->query($query, $params);
        return reset($results);
    }

    protected function execute($query, $params) {
        $prepared_statement = $this->connection->prepare($query);
        if ($params) {
        foreach ($params as $key => $param) {
            $prepared_statement->bindValue($key, $param);
        }
        }
        $prepared_statement->execute();
        return $prepared_statement;
    }

    public function insert($table, $entity) {
        $query = "INSERT INTO {$table} (";
        // INSERT INTO users (
        foreach ($entity as $column => $value) {
        $query .= $column . ", ";
        }
        // INSERT INTO users (username, 
        $query = substr($query, 0, -2);
        // INSERT INTO users (username, 
        $query .= ") VALUES (";
        // INSERT INTO users (username) VALUES (
        foreach ($entity as $column => $value) {
        $query .= ":" . $column . ", ";
        }
        // INSERT INTO users (username) VALUES (:username,
        $query = substr($query, 0, -2);
        // INSERT INTO users (username) VALUES (:username,
        $query .= ")";
        // INSERT INTO users (username) VALUES (:username)

        $statement = $this->connection->prepare($query);
        $statement->execute($entity); // SQL injection prevention
        $entity['id'] = $this->connection->lastInsertId();
        return $entity;
    }
}
?>