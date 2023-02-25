<?php

class Response{
    private $body;
    private string | null $contentType;

    public function __construct($responseCode, $contentType = null){
        http_response_code($responseCode);
        $this->contentType = $contentType;
    }

    public function setContentType($contentType){
        is_null($this->contentType) ? header("Content-Type: " . $contentType) : header("Content-Type: " . $contentType);
    }

    public function setBody($requestBody){
        switch($this->contentType){
            case "application/json":
                $this->body = json_encode($requestBody);
                break;
        }

    }

    public function sendResponse(){
        echo $this->body;
    }
}