<?php


class DatabaseTwo
{
    
    private $db_path = '../db/db.sqlite';
    
    public function dbConnection(){
        
        try{
            $conn = new PDO('sqlite:'.$this->db_path);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }
        catch(PDOException $e){
            echo "Connection error ".$e->getMessage(); 
            exit;
        }
          
    }
}