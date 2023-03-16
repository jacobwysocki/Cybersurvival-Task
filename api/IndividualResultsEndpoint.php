<?php

class IndividualResultsEndpoint extends Endpoint{
    public function __construct(Database $db, Request $request) {
        parent::__construct($db, $request);
      }

      public function GET(){
        // if(sizeof($this->uri) === 2){
        //   //return individual results of all use
        // }

        // if(sizeof($this->uri)===3){

        // }
      }
  
      public function POST(){
        $requestBody = $this->request->getREQUESTBODY();
        $experimentsID = isset($requestBody['experimentsID']) ? $requestBody['experimentsID'] : null;
        $userID = isset($requestBody['userID']) ? $requestBody['userID'] : null;
        $rankingOrder = isset($requestBody['rankingOrder']) ? $requestBody['rankingOrder'] : null;

        for($i = 0; $i < sizeof($rankingOrder); $i++){
          $this->db->POST("individualResults", ["userID" => $userID, "experimentsID" => $experimentsID, "itemID" => $rankingOrder[$i]["itemID"], "orderNo" =>$i+1]);
        }
      }
}