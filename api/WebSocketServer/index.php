<?php
error_reporting(E_ALL^E_DEPRECATED);
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

    // Make sure composer dependencies have been installed
    require __DIR__ . '/vendor/autoload.php';

/**
 * chat.php
 * Send any incoming messages to all connected clients (except sender)
 */
class MyChat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $id = null;
        
        if($conn->httpRequest->getUri()->getQuery()){
         $id = explode("=", $conn->httpRequest->getUri()->getQuery())[1];
        }
        $this->clients->attach($conn, ['groupID' => $id]);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);
        $userRank = $data["userRank"];
        $messageType = isset($data["messageType"]) ? $data["messageType"] : null;
        $currentGroupID  = 1;
        
        if($userRank === "user"){
            foreach($this->clients as $client){
                $groupID  = $this->clients->getInfo()["groupID"];
                if($currentGroupID == $groupID && $client != $from){
                    $client->send(json_encode([
                        "userRank" => "user",
                        "itemsUpdate" => $data["itemsUpdate"]]
                    ));
                }
            }
        }

        if($userRank === "admin" && $messageType === "command"){
            if(!isset($data["command"])) {
                //throw error or exception or something
            }
            switch($data["command"]){
                case "startIndividual":
                    foreach($this->clients as $client){
                        if($client != $from){
                            $client->send(json_encode(["command" => "startIndividual"]));
                        }
                    }
                break;
                case "startGroup":
                    foreach($this->clients as $client){
                        if($client != $from){
                            $response = array();
                            $response["command"] = "startGroup";
                            $items = json_decode(file_get_contents("http://127.0.0.1:8080/api/items"), true);
                            shuffle($items);
                            $response["items"] = $items;
                            $client->send(json_encode($response));
                        }

                    }
                break;
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new MyChat()
            )
        ),
        8082
    );

    $server->run();


    // {
    //     "userRank" : "admin",
    //     "messageType" : "command",
    //     "command" : "startIndividual"
    // }

    // {
    //     "userRank" : "admin",
    //     "messageType" : "command",
    //     "command" : "startGroup"
    // }