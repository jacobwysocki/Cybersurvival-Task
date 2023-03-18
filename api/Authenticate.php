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

        return array(
            "length" => 0,
            "message" => "Successfully logged in",
            "data" => $data
        );
    }


    protected function initialiseSQL() {
        $sql = "SELECT userID, email, password FROM users WHERE email = :email";
        $this->setSQL($sql);
        $this->setSQLParams(['email'=>$_SERVER['PHP_AUTH_USER']]);
    }

    private function validateAuthParameters() {
        if ( empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW']) ) {
            throw new ClientErrorException("Username and Password Required.", 401);
        }
    }

    private function validateUsername($data) {
        if (count($data)<1) {
            throw new ClientErrorException("Invalid Credentials.", 401);
        }
    }

    private function validatePassword($data) {
        if (!password_verify($_SERVER['PHP_AUTH_PW'], $data[0]['password'])) {
            throw new ClientErrorException("Invalid Credentials.", 401);
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