<?php
require config.php;

class DatabaseConnection{
    private $connection;

    public __construct(){
        if(self::$connection==null){
            $data_source="mysql:host=".host.";dbname=" . database_name . ";charset=utf8mb4";
            self::$connection_string=new PDO($data_source,username,password); 
        }
         
    }
    
    public getConnection(){
        return $this->$connection;
    }
}
?>