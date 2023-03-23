<?php
class UsersEndpoint extends Endpoint {
    public function __construct(Database $db, Request $request) {
      parent::__construct($db, $request);
    }
  
//    public function GET() {
//        $results = $this->db->SELECT_ALL("users");
//         return $results;
//    }
    public function GET(){
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
                assert(isset($this->uri[3]));
                return $this->db->SELECT_ONE_WHERE("users", "userID", $this->uri[3]);
                break;
            case 'admin':
                return $this->db->SELECT_ALL("users");
                break;
        }
    }

    public function PUT() {
        return $this->db->UPDATE("users", "userID", $this->request->getREQUESTBODY());
      
    }

    public function POST () {
        return $this->db->POST("users", $this->request->getREQUESTBODY());

    }
    public function DELETE() {
        return $this->db->DELETE("users", "userID", $this->uri[3]);
    }

}