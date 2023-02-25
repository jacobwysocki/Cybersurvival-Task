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

        public function __construct(Database $db, Request $request){
            $this->db = $db;
            $this->request = $request;
            $this->uri = array_filter(explode('/', $this->request->getURI()));
        }

        /**
         * GET() is the abstract method for a GET HTTP Request.
         *
         * @return [type]
         * 
         */
        abstract public function GET();

        /**
         * POST() is the abstract method for a POST HTTP Request.
         *
         * @return [type]
         * 
         */
        abstract public function POST();

        /**
         * PUT() is the abstract method for a PUT HTTP Request.
         * 
         * @return [type]
         * 
         */
        abstract public function PUT();

        /**
         * DELETE() is the abstract method for a DELETE HTTP Request.
         *
         * @return [type]
         * 
         */
        abstract public function DELETE();

    }