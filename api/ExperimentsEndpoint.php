<?php

class ExperimentsEndpoint extends Endpoint{
    public function __construct(Database $db, Request $request) {
        parent::__construct($db, $request, "experiments");
    }

    public function GET(){
        //by default, it will show app experiments, the ability to show currently available experiments through parameters is being considered
        if(sizeof($this->uri) === 2){
            return $this->db->SELECT_ALL($this->tableName);
        }
        //add checks to check if the third value is numeric
        if(sizeof($this->uri) === 3){
            return $this->db->SELECT_ONE($this->tableName, $this->tableName."ID", $this->uri[3]);
        }

        // if(sizeof($this->uri)===4){
        //     //check if the third value is numeric

        //     switch($this->uri[4]){
        //         case "join":

        //             break;
        //         case "start":
        //             //check if user is admin
        //             break;
        //         default:
        //             break;
        //     }
        // }
    }

    public function POST(){
        print_r($this->uri);
        // return $this->db->POST("experiments", $this->request->getREQUESTBODY());
    }

    public function PUT () {
        return $this->db->UPDATE($this->tableName, $this->tableName."ID", $this->request->getREQUESTBODY());

    }
    public function DELETE() {
        return $this->db->DELETE($this->tableName, $this->tableName."ID", $this->uri[3]);
    }
}