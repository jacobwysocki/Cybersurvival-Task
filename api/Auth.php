<?php
/**
 * The Auth abstract class defines the two methods required to
 * authenticate and authorise a user while leaving their implementation 
 * to the respective subclass implementing the different Auth methods
 * supported by HTTP.
 */
    abstract class Auth extends Endpoint{
        protected Database $db;

        function __construct($db){
            $this->db = $db;
        }

        /**
         * authenticate() returns a booloean value.
         * true if the username/password combo matches an entry in the database,
         * false if it doesn't
         * 
         * @return bool
         * 
         */
        public abstract function authenticate() : bool;

        /**
         * authenticate() returns a string containing the rank of the user's
         * credentials after they're validated by the authenticate() function
         *
         * @return string
         * 
         */
        public abstract function authorise() : string;

        /**
         * AuthFactory() is a static method that will, based on the Authorisation
         * string present in the HTTP header of the Request passed to it, instantiate 
         * and then return the appropriate Auth object to the caller.
         *
         * @param Request $request
         * @param Database $db
         * 
         * @return Auth
         * 
         */
        public static function AuthFactory(Request $request, Database $db) : Auth | null{
            $temp = $request->getHEADERS();
            $authHeader = "None Invalid";
            if(isset($temp['Authorization'])) $authHeader = $temp['Authorization'];
            
            $authType = explode(' ', $authHeader)[0];
            $authString = explode(' ', $authHeader)[1];

            switch($authType){
                case 'Basic':
                    return new BasicAuth($authString, $db);
                    break;
                default:
                    return null;
            }
        }
}