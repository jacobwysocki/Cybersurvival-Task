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

        $auth = new Authenticate();
        if(isset($auth)){
            if($auth->authenticate()){
                $rank = $auth->getRank();
                $rank = $rank[0]["rankName"];
            }
        }

        switch($rank){
            case "user":
                //add user to group
                assert(isset($this->uri[3]));
                //add check for number of people in a group
                $this->db->POST("userGroup",array(
                    "userID" => $auth->getUserID(),
                    "groupID" => $this->uri[3]
                ));
                break;
            case 'admin':
                return $this->db->POST("groups", $this->request->getREQUESTBODY());
                break;
        }
    }
}