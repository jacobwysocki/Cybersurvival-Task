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
    protected $clientids;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->clientids = array();
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo($conn->httpRequest->getUri()->getQuery());
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);
        $userRank = $data["userRank"];
        $messageType = $data["messageType"];
        
        if($userRank === "user"){
            
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
