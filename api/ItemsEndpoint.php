<?php
class ItemsEndpoint extends Endpoint {
    public function __construct(Database $db, Request $request) {
      parent::__construct($db, $request);
    }
  
    public function GET() {
        $rank = "null";

        $auth = new Authenticate();
        if(isset($auth)){
            if($auth->authenticate()){
                $rank = $auth->getRank();
                $rank = $rank[0]["rankName"];
            }
        }

        $results = $this->db->SELECT_ALL("items");
        switch($rank){
            case "user":
                shuffle($results);
                return $results;
                break;
            case 'admin':
                return $results;
                break;
        }
    }

    public function PUT() {
        return $this->db->UPDATE("items", "itemID", $this->request->getREQUESTBODY());
      
    }

    public function POST () {
        return $this->db->POST("items", $this->request->getREQUESTBODY());

    }
    public function DELETE() {
        return $this->db->DELETE("items", "itemID", $this->uri[3]);
    }
}
