<?php

/**
 * This endpoint is used to secure the password of a user when they register
 */

class passwordRegister extends \Endpoint
{
    public function __construct(\Database $db, \Request $request)
    {
        parent::__construct($db, $request);
    }

    public function getPassword($request, $response, $args)
    {
        try {
            // Get the request body data
            $requestBody = $request->getREQUESTBODY();

            // Check if the 'password' key exists in the request body data array
            if (isset($requestBody['password'])) {
                // Get the password from the request
                $password = $requestBody['password'];

                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Save the password to the database
                $stmt = $this->db->prepare("INSERT INTO users (password) VALUES (:password)");
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->execute();

                // Return a success message
                $responseObj = new Response(200);
                $responseObj->setBody(['message' => 'User registered successfully']);
                $responseObj->sendResponse();
                return $response;
            } else {
                // Return an error message if the 'password' key is missing
                throw new Exception('Missing password');
            }
        } catch (Exception $e) {
            // Return an error response with the error message
            $responseObj = new Response(400);
            $responseObj->setBody(['message' => $e->getMessage()]);
            $responseObj->sendResponse();
            return $response;
        }
    }
}
