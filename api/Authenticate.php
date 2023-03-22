<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access, content-type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include 'config/private.php';
use FirebaseJWT\JWT;

class Authenticate extends EndpointTwo
{
    private array $userData;

    public function authenticate(){
        $db = new DatabaseTwo('../db/db.sqlite');

        $username = isset($_SERVER['PHP_AUTH_USER']) ? htmlspecialchars($_SERVER['PHP_AUTH_USER']) : '';
        $data = $db->executeSQL("SELECT * FROM users WHERE email = ?", [$username]);

        if(empty($data)){
            return false;
        };

        $passwordHash = $data[0]["password"];

        if(password_verify($_SERVER['PHP_AUTH_PW'], $passwordHash)){
            $this->userData = $data[0];
            return true;
        }else return false;
    }


    public function getRank(){
        if(!isset($this->userData["rankID"])){
            // rankID key not present in userData array
            return false;
        }
        $db = new DatabaseTwo('../db/db.sqlite');
        $rankID = $this->userData["rankID"];
        assert(is_numeric($rankID));
        return $db->executeSQL("SELECT rankName FROM UserRank WHERE rankID = ?", [$rankID]);
    }

    public function __construct() {

    }

    public function POST()
    {
        $db = new DatabaseTwo('../db/db.sqlite');

        $this->validateAuthParameters();


        $this->initialiseSQL();

        // Execute the SQL query and retrieve the results
        $queryResult = $db->executeSQL($this->getSQL(), $this->getSQLParams());

        // Validate the username and password
        $this->validateUsername($queryResult);
        $this->validatePassword($queryResult);
        $data['token'] = $this -> createJWT($queryResult);
        $rank = $this->getRank();

        return array(
            "length" => 0,
            "message" => "Successfully logged in",
            "data" => $data,
            "rank" => $rank
    );
    }


    protected function initialiseSQL() {
        $sql = "SELECT userID, email, password FROM users WHERE email = :email";
        $this->setSQL($sql);
        $this->setSQLParams(['email'=>$_SERVER['PHP_AUTH_USER']]);
    }

    private function validateAuthParameters() {
        if ( empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW']) ) {
            $errorResponse = array(
                "status" => "error",
                "message" => "Username and Password are required."
            );
            http_response_code(401);
            echo json_encode($errorResponse);
            exit;
        }
    }

    private function validateUsername($data) {
        if (count($data)<1) {
            $errorResponse = array(
                "status" => "error",
                "message" => "Invalid Credentials."
            );
            http_response_code(401);
            echo json_encode($errorResponse);
            exit;
        }
    }

    private function validatePassword($data) {
        if (!password_verify($_SERVER['PHP_AUTH_PW'], $data[0]['password'])) {
            $errorResponse = array(
                "status" => "error",
                "message" => "Invalid Credentials."
            );
            http_response_code(401);
            echo json_encode($errorResponse);
            exit;
        }
    }

    private function createJWT($queryResult) {

        $secretKey = SECRET;
        $time = time();

        $tokenPayload = [
            'iat' => $time,
            'exp' => strtotime('+1 day', $time),
            'iss' => $_SERVER['HTTP_HOST'],
            'sub' => $queryResult[0]['userID']
        ];

        $jwt = JWT::encode($tokenPayload, $secretKey, 'HS256');

        return $jwt;
    }

}