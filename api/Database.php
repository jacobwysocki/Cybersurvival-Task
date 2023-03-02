<?php
// use Dotenv\Dotenv;

    abstract class Database{

        protected $connection;
        // protected $databaseDescription;

        public function __construct(){
            // $this->databaseDescription = json_decode(file_get_contents("../db/DatabaseDescription.json"));
        }

        public static function DatabaseFactory() : Database{
            $dotenv = Dotenv\Dotenv::createImmutable("./config/");
            $dotenv->load();

            $dotenv->required('DATABASE_ENGINE')->notEmpty();
            $engine = $_ENV['DATABASE_ENGINE'];

            switch($engine){
                case 'MYSQL':
                    $dotenv->required('HOST')->notEmpty();
                    $dotenv->required('PORT')->notEmpty();
                    $dotenv->required('DATABASE')->notEmpty();
                    $dotenv->required('USERNAME')->notEmpty();
                    $dotenv->required('PASSWORD')->notEmpty();
                    return new MySQLDatabase();
                break;
                case "SQLITE":
                    $dotenv->required('FILEPATH')->notEmpty();
                    return new SQLiteDatabase();
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

        public function getDatabase(){
            return $this->connection;
        }
    }
