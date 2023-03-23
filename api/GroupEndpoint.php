<?php

class GroupEndpoint extends Endpoint{

    public function __construct(Database $db, Request $request) {
        parent::__construct($db, $request);
    }

    public function GET(){
        return $this->db->SELECT_ALL("groups");
    }

    public function POST(){
        $rank = "null";

        $auth = Auth::AuthFactory($this->db, $this->request);
        if(isset($auth)){
            if($auth->authenticate()){
                $rank = $auth->getRank();
            }else{
                $r = new \Response(401);
                $r->setBody(array(
                    "status" => "error",
                    "message" => "Invalid Credentials."
                ));
                exit();
            }
        }

        switch($rank){
            case "user":
                //add user to group
                assert(isset($this->uri[3]));
                //add check for number of people in a group
                $this->db->POST("userGroup",array(
                    "userID" => $auth->getUserData()["userID"],
                    "groupID" => $this->uri[3]
                ));
                break;
            case 'admin':
                return $this->db->POST("groups", $this->request->getREQUESTBODY());
                break;
        }
    }
}