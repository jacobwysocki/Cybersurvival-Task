<?php

/**
 * The abstract Endpoint class is intended to be implemented
 * by concrete subclasses for each endpoint.
 * 
 * By reading the Endpoints.json file and dynamically initiating
 * the specific endpoint class, a modular design is achieved allowing
 * for easy and straightfoward expansion of the API.
 * of the behaviour of different endpoints.
 */
    abstract class Endpoint{
        protected Database $db;
        protected Request $request;
        protected array $uri;
        protected array $parametersAllowed;
        protected string $tableName;

        public function __construct(Database $db, Request $request, string $tableName = ""){
            $this->db = $db;
            $this->request = $request;
            $this->uri = array_filter(explode('/', $this->request->getURI()));
            $this->tableName = $tableName;
        }

        /**
         * GET() is the abstract method for a GET HTTP Request.
         *
         * @return [type]
         * 
         */
        public function GET(){}

        /**
         * POST() is the abstract method for a POST HTTP Request.
         *
         * @return [type]
         * 
         */
        public function POST(){}

        /**
         * PUT() is the abstract method for a PUT HTTP Request.
         * 
         * @return [type]
         * 
         */
        public function PUT(){}

        /**
         * DELETE() is the abstract method for a DELETE HTTP Request.
         *
         * @return [type]
         * 
         */
        public function DELETE(){}

        // protected function checkValidIdentifier($identifier){
        //     isset($this->tableName);
        //     $validIDs = $this->db->getDatabase("SELECT " . $this->tableName."ID". " from " . $this->tableName);
        //     print_r($validIDs);
        // }

    }