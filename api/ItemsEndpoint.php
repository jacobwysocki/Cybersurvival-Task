<?php
class ItemsEndpoint extends Endpoint {
    public function __construct(Database $db, Request $request) {
      parent::__construct($db, $request);
    }
  /**
  * Item Endpoint 
  *
  * @author Gabriela Piatek
  */

    public function GET() {
        $rank = "null";

        $auth = Auth::AuthFactory($this->db, $this->request);
        if(isset($auth)){
            if($auth->authenticate()){
                $rank = $auth->getRank();
            }else{
                new Response(401);
                exit();
            }
}

        $results = $this->db->SELECT_ALL("items");
        switch($rank){
            case "user":
                shuffle($results);
                return $results;
                break;
            case 'admin':
            case 'null':
                $l = sizeof($this->uri);
                switch($l){
                    case 2:
                        return $this->db->SELECT_ALL("items");
                        break;
                    case 3:
                        return $this->db->SELECT_ONE("items", "itemID", $this->uri[3]);
                        break;
                    }
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
