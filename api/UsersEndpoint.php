<?php
class UsersEndpoint extends Endpoint {
    public function __construct(Database $db, Request $request) {
      parent::__construct($db, $request);
    }
  
    public function GET() {
        $results = $this->db->SELECT_ALL("users");
         return $results;
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