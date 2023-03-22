<?php

class EndpointAuth extends Endpoint{
    public function __construct(Database $db, Request $request){
        parent::__construct($db, $request, "users", "userID");
    }

    //The GET method for this endpoint generates a valid access token
    public function GET(){
        $auth = Auth::AuthFactory($this->db, $this->request);

        if(isset($auth)){
            if($auth->authenticate()){
                $data = array();
                $keys = array_keys($auth->getUserData());
                
                foreach($keys as $key){
                    $data[$key] = $auth->getUserData()[$key];
                }
                $data['rank'] = $auth->getRank();
                $data['token'] = $auth->createJWT();
                $data['message'] =  "Successfully logged in";
                return $data;
        }
     } else return false;
    }

    //The POST method for this endpoint validates a previously generated access token
    public function POST(){
        $auth = Auth::AuthFactory($this->db, $this->request);
        return isset($auth) && $auth->authenticate();
    }

    public function PUT(){}
    public function DELETE(){}
}