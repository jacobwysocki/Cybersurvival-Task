<?php

class BasicAuth extends Auth{
    private String $authString;

    public function __construct(Database $database, $authString){
        parent::__construct($database);
        $this->authString = $authString;
    }

    public function authenticate(){
        $temp = base64_decode($this->authString);
        $username = explode(':', $temp)[0];
        $password = explode(':', $temp)[1];

        if(!isset($username) || !isset($password)){
            return 401;
        }

        $accountData = $this->db->SELECT_ONE_WHERE("users", "email", $username);
        if($accountData == null){
            return false;
        };
        
        $passwordHash = $accountData["password"];

        if(password_verify($password, $passwordHash)){
            $this->userData = $accountData;
            return true;
        }else return false;

    }
}