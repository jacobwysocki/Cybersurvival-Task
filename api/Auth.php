<?php

use Firebase\JWT\JWT;
/**
 * This is the authentication endpoint. It is used to authenticate a user.
 */
class Auth extends \Endpoint
{
private string $secretKey ='DUNNO_YET';
public function __construct(Database $db, Request $request, Response $response)
{
    parent::__construct($db, $request, $response);

}
public function authenticate($request,$response, $args){
    try{
        // check for authorisation header
        $headers = $request->getHEADERS();
        if(!isset($headers['Authorization'])){
            throw new Exception('No authorisation header');
        }
        // check for bearer token
        $authHeader = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $authHeader);
        //verify  the token and retrieve the user data
        $jwt = new Firebase\JWT\JWT;
        $decoded = $jwt->decode($token, $this->secretKey, array('HS256'));
        $username = $decoded->username;
        $password = $decoded->password;

        // check if the user exists
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $userCount = $stmt->fetchColumn();

        if ($userCount=== 1) {
            //retrieve the user data
            $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $userData = $stmt->fetch();
            // check if the password matches
            if (password_verify($password, $userData['password'])) {
                //return a success message with a new token

              $jwtToken = $this->generateToken($userData);

                $responseObj = new Response(200);
                $responseObj->setHeader('Authorization: Bearer ' . $jwtToken);
                $responseObj->setBody(['message' => 'User authenticated successfully']);
                $responseObj->sendResponse();
                return $response;
            } else {
                throw new Exception('Invalid password');

            }
        } else {
            throw new Exception('Invalid username');
            }
    }catch(Exception $e){
        $responseObj = new Response(401);
        $responseObj->setBody(['message' => $e->getMessage()]);
        $responseObj->sendResponse();
        return $response;
    }


}
private function generateToken( $userData):string
{
    $tokenData = array(
        'username' => $userData['username'],
        'iat' => time(),
        'expires' => strtotime('+1 day', time()),// token expires in 1 day
        'iss' => $_SERVER['HTTP_HOST']
    );

    return JWT::encode($tokenData, $this->secretKey, 'HS256');

}
}