<?php
/**
 * The Request class will parse the incoming request to the API.
 * Variables are set up to represent the Universal Resource Identifier, 
 * an array with the HTTP Headers received, the Request Body and the HTTP Request Method.
 * Appropriate Getters are set up to retrieve these values.
 * No Setters are needed.
 */
    class Request{
        private string $URI;
        private array $HEADERS;
        private string $HTTP_METHOD;
        private array $REQUEST_BODY;
        private array $auth;
        private array $parameters;
        
        /**
         *  Simple constructor for the Request class.
         *  No arguments are needed as the data needed is read from
         * either PHP's superglobal arrays or the input stream.
         */
        public function __construct(){
            $this->URI = explode('?', $_SERVER['REQUEST_URI'])[0];

            if (array_key_exists(1, explode('?', $_SERVER['REQUEST_URI']))){

                foreach(explode('&', explode('?', $_SERVER['REQUEST_URI'])[1]) as $keyValuePair){
                    $key = explode('=', $keyValuePair)[0];
                    $value = explode('=', $keyValuePair)[1];
                    $this->parameters[$key] = $value;
                }
                
            }

            $this->HEADERS = getallheaders();
            $this->HTTP_METHOD = $_SERVER['REQUEST_METHOD'];

            $inputStreamData = file_get_contents("php://input");
            if(json_decode($inputStreamData) !== null){
                $this->REQUEST_BODY = json_decode($inputStreamData, true);
            }

            if(isset($this->getHEADERS()['Authorization'])){
                $authHeader = $this->getHEADERS()['Authorization'];
                $authType = explode(' ', $authHeader)[0];
                $authString = explode(' ', $authHeader)[1];
            }else if(isset($this->getHEADERS()['authorization'])){
                $authHeader = $this->getHEADERS()['authorization'];
                $authType = explode(' ', $authHeader)[0];
                $authString = explode(' ', $authHeader)[1];
            }

            if($this->HTTP_METHOD == "POST" || $this->HTTP_METHOD == "PUT"){
                if(!isset($this->REQUEST_BODY)){
                    new Response(400);
                    exit;
                }else if(!isset($this->HEADERS['Content-Length'])){
                    new Response(411);
                    exit;
                }
            }else{
                if(isset($this->REQUEST_BODY)){
                    new Response(400);
                    exit;
                }
            }

        }

        private function handleAuth($authString, $authHeader){
            switch($authString){
                case 'Basic' :
                    
                break;
                case 'Bearer' :
                    
                break;
            }
        }

        /**
         * Returns the HTTPMethod variable that contains the HTTP method received by the server.
         *
         * @return string
         * 
         */
        public function getHTTPMethod() : string{
            return $this->HTTP_METHOD;
        }

        /**
         * Returns the URI variable that contains the URI received by the server.
         *
         * @return string
         * 
         */
        public function getURI() : string{
            return $this->URI;
        }

        /**
         * Returns the HEADERS array that contains an array with all the 
         * headers received by the server.
         *
         * @return array
         * 
         */
        public function getHEADERS() : array{
            return $this->HEADERS;
        }

        /**
         * Returns the REQUESTBODY string that contains the body of the 
         * request received by the server.
         *
         * @return string
         * 
         */
        public function getREQUESTBODY() : array{
            return $this->REQUEST_BODY;
        }

        public function getPARAMETERS() : array|null {
            return isset($this->parameters) ? $this->parameters : null;
        }
    }