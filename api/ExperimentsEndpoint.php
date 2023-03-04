<?php

class ExperimentsEndpoint extends Endpoint{
    public function __construct(Database $db, Request $request) {
        parent::__construct($db, $request);
    }

    public function GET(){
        if(sizeof($this->uri) === 2){
            return $this->db->SELECT_ALL("experiments");
        }
        //add checks to check if the third value is numeric
        if(sizeof($this->uri) === 3){
            return $this->db->SELECT_ONE("experiments", "experimentsID", $this->uri[3]);
        }
    }

    public function POST(){
        return $this->db->POST("experiments", $this->request->getREQUESTBODY());
    }

    public function PUT () {
        return $this->db->UPDATE("experiments", "experimentsID", $this->request->getREQUESTBODY());

    }
    public function DELETE() {
        return $this->db->DELETE("experiments", "experimentsID", $this->uri[3]);
    }
}