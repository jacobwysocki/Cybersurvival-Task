<?php

class Response{
    private $body;

    public function __construct($responseCode){
        http_response_code($responseCode);
        header("Content-Type: application/json; charset=UTF-8");
    }

    public function setHeader($string){
        header($string);
    }

    public function setBody($requestBody){
        $this->body = json_encode($requestBody);
    }

    public function sendResponse(){
        echo $this->body;
    }
}