<?php
class SQLiteDatabase extends Database{
    private String $filepath;

    public function __construct(){
        parent::__construct();
        $this->filepath = $_ENV['FILEPATH'];

        try{
            $this->connection = new PDO("sqlite:$this->filepath");
        }catch(PDOException $e){
            echo "Error instantiating database connection. " .$e;
        }
    }

    public function SELECT_ALL($resource){
            $query = $this->connection->prepare("SELECT * FROM " . $resource);
        try{
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            throw new databaseException("Query error, " . $e);
        }
    }

    public function SELECT_ONE($resource, $primaryKey, $id){
        $query = $this->connection->prepare("SELECT * FROM ".$resource." 
                                                        WHERE ".$primaryKey." = ?");
        $query->bindParam(1, $id);
        try{
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            throw new databaseException("Query error, " $e);
        }
    }

    public function SELECT_COLUMN($field, $table) {
        $query = $this->connection->prepare("SELECT " . $field . " FROM " . $table);
        try{
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            throw new databaseException("Query error, " $e);
        }
    }

    public function SELECT_ONE_WHERE($resource, $field, $value){
        $query = $this->connection->prepare("SELECT * FROM " . $resource . " WHERE " . $field . " = ?");
        $query->bindParam(1, $value);
        try{
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            return sizeof($result) === 1 ? $result[0] : false;
        }catch(PDOException $e){
            throw new databaseException("Query error, " $e);
        }
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
                $query .= array_keys($resourceData)[$i] . " = :". strval(array_keys($resourceData)[$i]) . " ";
            }else $query .= array_keys($resourceData)[$i] . " = :" .strval(array_keys($resourceData)[$i])  . ", ";
        }

        $query .= "WHERE " . $primaryKey . " = :" . $primaryKey;
        try{
            $temp = $this->connection->prepare($query);
            return $temp->execute($resourceData);
        }catch (Exception $e){
            echo $e;
        }
    }

    public function DELETE($resource, $primaryKey, $id){
        $query = $this->connection->prepare("DELETE FROM ".$resource." 
                                                        WHERE ".$primaryKey." = ?");
        $query->bindParam(1, $id);
        return $query->execute();
    }
}