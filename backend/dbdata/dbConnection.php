<?php

class DatabaseConnection{
    private static $connection=null;

    public function __construct(){
        if(self::$connection==null){
            try{
                $data_source="mysql:host=".host.";dbname=" . database_name . ";charset=utf8mb4";
                self::$connection=new PDO($data_source,username,password);
            } catch (PDOException $e) {
                throw new PDOException("Database connection failed: " . $e->getMessage());
            } 
        }
         
    }
    
    public static function getInstance() {
        if (self::$connection === null) {
            new self();
        }
        return self::$connection;
    }
}
?>