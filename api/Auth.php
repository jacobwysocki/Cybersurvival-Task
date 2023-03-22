<?php
use Firebase\JWT\JWT;

abstract class Auth{
    protected Database $db;
    protected Array $userData;

    public function __construct(Database $database){
        $this->db = $database;
    }

    public static function AuthFactory(Database $db, Request $request){
        $authType = null;
        $payload = null;
        if(isset($request->getHEADERS()['Authorization'])){
            $authHeader = $request->getHEADERS()['Authorization'];
            $authType = explode(' ', $authHeader)[0];
            $payload = explode(' ', $authHeader)[1];
        }else if(isset($request->getHEADERS()['authorization'])){
            $authHeader = $request->getHEADERS()['authorization'];
            $authType = explode(' ', $authHeader)[0];
            $payload = explode(' ', $authHeader)[1];
        }

        //assert authType and payload is not null
        switch($authType){
            case "basic":
            case "Basic":
                return new BasicAuth($db, $payload);
                break;
            case "bearer":
            case "Bearer":
                return new JWTAuth($db, $payload);
                break;
            default:
                return null;
        }
    }

    public abstract function authenticate();

    public function getRank(){
        $rankID = $this->userData["rankID"];
        assert(is_numeric($rankID));
        return $this->db->SELECT_ONE("userRank", "rankID", $rankID)["rankName"];
    }

    public function createJWT(){
        assert($this->authenticate());
        $time = time();

            $tokenPayload = [
                'iat' => $time,
                'exp' =>strtotime('+1 day', $time),
                'iss' => $_SERVER['HTTP_HOST'],
                'sub' => $this->userData["userID"]
            ];

            $jwt = JWT::encode($tokenPayload, SECRET, 'HS256');

            return $jwt;
    }

    public function getUserData(){
        return $this->userData;
    }
}