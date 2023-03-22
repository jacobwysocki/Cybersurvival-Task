<?php
    class BaseEndpoint extends Endpoint{
        public function __construct(Database $db, Request $request){
            parent::__construct($db, $request);
        }

        public function GET(){
            return "hi";
        }
    }