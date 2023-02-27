<?php
    class MySQLDatabase extends Database{
        private String $username, $password, $hostname, $port, $database;

        public function __construct(){
            parent::__construct();
            $this->hostname = $_ENV['HOST'];
            $this->port = $_ENV['PORT'];
            $this->database = $_ENV['DATABASE'];
            $this->username = $_ENV['USERNAME'];
            $this->password = $_ENV['PASSWORD'];

            try{
                $this->connection = new PDO("mysql:host=".$this->hostname.":".$this->port.";dbname=".$this->database,
                    $this->username, $this->password);
            }catch(PDOException $e){
                echo "Error instantiating database connection. " .$e;
            }
        }

        public function SELECT_ALL($resource){
            $query = $this->connection->prepare("SELECT * FROM " . $resource);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_CLASS);
        }

        public function SELECT_ONE($resource, $primaryKey, $id){
            $query = $this->connection->prepare("SELECT * FROM ".$resource." 
                                                            WHERE ".$primaryKey." = ?");
            $query->bindParam(1, $id);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        public function SELECT_ONE_WHERE($resource, $field, $value){
            $query = "SELECT * FROM " . $resource . " WHERE " . $field . " = ?";
            $query = $this->connection->prepare($query);
            $query->bindParam(1, $value);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            return sizeof($result) === 1 ? $result[0] : false;
        }

        public function SELECT_WHERE($resource, $params){
            $query = "SELECT * FROM " . $resource . " WHERE ";
            $l = sizeof(array_keys($params));
            $s = array_keys($params);
            for($i = 0; $i < $l; $i++){
                if($i == $l-1){
                    $query .= array_keys($params)[$i] . " = :" . array_keys($params)[$i];
                }else $query .= array_keys($params)[$i]  . " = :" . array_keys($params)[$i] . " AND ";
            }

            $query = $this->connection->prepare($query);
            $query->execute($params);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function POST($resource, $resourceData){
            $query = "INSERT INTO " .$resource." (";
            $l = sizeof(array_keys($resourceData));
            $s = array_keys($resourceData);
            for($i = 0; $i != $l; $i++){
                if($i == $l-1){
                    $query .= array_keys($resourceData)[$i];
                }else $query .= array_keys($resourceData)[$i] . ", ";
            }
            $query .= ") VALUES (";

            for($i = 0; $i != $l; $i++){
                if($i == $l-1){
                    $query .= ":" . array_keys($resourceData)[$i];
                }else $query .= ":" . array_keys($resourceData)[$i] .", ";
            }
            $query .= ")";
        
            $temp = $this->connection->prepare($query);
            return $temp->execute($resourceData);
        }

        public function UPDATE($resource, $primaryKey, $resourceData){
            $query = "UPDATE " . $resource . " SET ";
            $l = sizeof($resourceData);

            for($i = 0; $i < $l; $i++){
                if($i == $l-1){
                    $query .= array_keys($resourceData)[$i] . " = :". array_keys($resourceData)[$i] . " ";
                }else $query .= array_keys($resourceData)[$i] . " = :" .array_keys($resourceData)[$i]  . ", ";
            }

            $query .= "WHERE " . $primaryKey . " = :" . $primaryKey;
            
            $temp = $this->connection->prepare($query);
            return $temp->execute($resourceData);
        }

        public function DELETE($resource, $primaryKey, $id){
            $query = $this->connection->prepare("DELETE FROM ".$resource." 
                                                            WHERE ".$primaryKey." = ?");
            $query->bindParam(1, $id);
            return $query->execute();
        }
    }