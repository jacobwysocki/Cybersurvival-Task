<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTAuth extends Auth{
    private String $token;

    public function __construct(Database $database, $token){
        parent::__construct($database);
        $this->token = $token;
    }

    public function authenticate(){
        try{
            $decoded = JWT::decode($this->token, new Key(SECRET, 'HS256'));

            if($decoded->iss != $_SERVER['HTTP_HOST']){
                new \Response(401);
            }

            if(!is_numeric($decoded->sub)){
                new \Response(401);
            }
            
            $this->userData = $this->db->SELECT_ONE_WHERE("users", "userID", $decoded->sub);

            return true;
        } catch (\Exception $e) {
            echo $e;
            //check the GH page and cover those exceptions
            $r = new \Response(401);
            $r->setBody(array(
                "status" => "error",
                "message" => "Invalid Credentials."
            ));
          }
    }
}