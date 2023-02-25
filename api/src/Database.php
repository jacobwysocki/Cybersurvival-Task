<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    abstract class Database{

        protected $connection;
        // protected $databaseDescription;

        public function __construct(){
            // $this->databaseDescription = json_decode(file_get_contents("../db/DatabaseDescription.json"));
        }

        public static function DatabaseFactory() : Database{

            $engine = $_ENV['DATABASE_ENGINE'];

            switch($engine){
                case 'MYSQL':
                    return new MySQLDatabase();
                break;
                default:
                //LAUNCH ERROR BECAUSE NO DB ENGINE SPECIFIED
            }
        }
        
        //GET ALL
        public abstract function SELECT_ALL($resource);

        //GET ONE
        public abstract function SELECT_ONE($resource, $primaryKey, $id);

        public abstract function SELECT_ONE_WHERE($resource, $field, $value);

        public abstract function SELECT_WHERE($resource, $params);

        //POST
        public abstract function POST($resource, $resourceData);

        //PUT
        public abstract function UPDATE($resource, $primaryKey, $resourceData);

        //DELETE
        public abstract function DELETE($resource, $primaryKey, $id);

        //UpdateCustomer
//        public function updateCustomer($assocArray){
//
//            $query=$this->connection->prepare(
//                "UPDATE CustomerData SET
//                        customerName = ?,
//                        phoneNumber = ?,
//                        emailAddress = ?,
//                        customerAddress = ?,
//                        postCode = ?,
//                        contactByPhone = ?,
//                        contactByEmail = ? WHERE customerID = ?");
//
//            $query->bindParam(1, $assocArray->customerName);
//            $query->bindParam(2, $assocArray->phoneNumber);
//            $query->bindParam(3, $assocArray->emailAddress);
//            $query->bindParam(4, $assocArray->customerAddress);
//            $query->bindParam(5, $assocArray->postCode);
//            $query->bindParam(6, $assocArray->contactByPhone);
//            $query->bindParam(7, $assocArray->contactByEmail);
//            $query->bindParam(8, $assocArray->customerID);
//
//            if($query->execute()){
//                return true;
//            }else return $query->errorCode();
//        }

        //createCustomer
//        public function createCustomer($assocArray){
//            $query=$this->connection->prepare(
//                "INSERT INTO CustomerData(customerName, phoneNumber, emailAddress, customerAddress, postCode, contactByPhone, contactByEmail) VALUES(?, ?, ?, ?, ?, ?, ?)");
//
//            $query->bindParam(1, $assocArray->customerName);
//            $query->bindParam(2, $assocArray->phoneNumber);
//            $query->bindParam(3, $assocArray->emailAddress);
//            $query->bindParam(4, $assocArray->customerAddress);
//            $query->bindParam(5, $assocArray->postCode);
//            $query->bindParam(6, $assocArray->contactByPhone);
//            $query->bindParam(7, $assocArray->contactByEmail);
//
//            if($query->execute()){
//                return true;
//            }else return $query->errorCode();
//        }

        //deleteCustomer
//        public function deleteCustomer($customerID){
//            $query = $this->connection->prepare("DELETE FROM CustomerData WHERE customerID = ?");
//            $query->bindParam(1, $customerID);
//            if($query->execute()){
//                return true;
//            }else return $query->errorCode();
//        }
    }
