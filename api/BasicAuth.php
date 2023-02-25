<?php
/**
 * The BasicAuth class is an implementation of the HTTP Basic authentication method.
 */
    class BasicAuth extends Auth{
        private String $username,  $password;
        /**
         * userData will store all data related to the user attempting to authenticate
         * in an effort to make authorisation easier by not having to query the database again.
         *
         * @var [type]
         */
        private $userData;

        /**
         * When this class is instantiated the constuctor will decode
         * the auth string and assign its contents to the respective variables 
         *
         * @param string $authString
         * @param Database $db
         * 
         */
        function __construct(string $authString, Database $db){
            parent::__construct($db);

            $temp = base64_decode($authString);
            $this->username = explode(':', $temp)[0];
            $this->password = explode(':', $temp)[1];
        }

        /**
         * authenticate() is the concrete implementation of its abstract counterpart.
         * It queries the database and returns a boolean value:
         * returns true if the username and password strings match what's stored in the database;
         * returns false if they don't match.
         *
         * @return bool
         * 
         */
        public function authenticate() : bool{
            $resource = 'SystemUser';
            // $this->userData = $this->db->getUserData($field, $this->username);
            
            $this->userData = $this->db->SELECT_ONE_WHERE($resource, "userName", $this->username);
            
            if($this->userData){
                return password_verify($this->password, $this->userData['passwordHash']);
            }else{
                return false;
            }
        }

        /**
         * authorise() is the concrete implementation of its abstract counterpart.
         * It uses the userData variable to obtain the ID of the rank the current user has,
         * then the rank table of the database is queried to return the rank string.
         *
         * @return string
         * 
         */
        public function authorise() : string{

            //gets the userRankID from the UserData Object
            $value = $this->userData['rankID'];

            
            $result = $this->db->SELECT_ONE("UserRank", "rankID", $value);

            return $result['rankName'];
        }
    }