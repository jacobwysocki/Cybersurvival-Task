<?php
class ItemsEndpoint extends Endpoint {
    public function __construct(Database $db, Request $request) {
      parent::__construct($db, $request);
    }
  
    public function GET() {
        $results = $this->db->SELECT_ALL("items");
         return $results;
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
