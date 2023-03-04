<?php

use function PHPUnit\Framework\isEmpty;

class GroupEndpoint extends Endpoint{

    public function __construct(Database $db, Request $request) {
        parent::__construct($db, $request);
    }

    public function GET(){
        return $this->db->SELECT_ALL("groups");
    }

    public function POST(){
        return $this->db->POST("groups", $this->request->getREQUESTBODY());
    }
}