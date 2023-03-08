<?php
    class Request{
        private string $URI;
        private array $HEADERS;
        private string $HTTP_METHOD;
        private array $REQUEST_BODY;
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
            
            // if($this->HTTP_METHOD == "POST" || $this->HTTP_METHOD == "PUT"){
            //     if(!isset($this->REQUEST_BODY)){
            //         new Response(400);
            //         exit;
            //     }else if(!isset($this->HEADERS['Content-Length'])){
            //         new Response(411);
            //         exit;
            //     }
            // }else{
            //     if(isset($this->REQUEST_BODY)){
            //         new Response(400);
            //         exit;
            //     }
            // }

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
