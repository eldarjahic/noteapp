<?php
require_once __DIR__ ."/../../config.php";

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
}
?>