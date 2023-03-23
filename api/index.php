<?php
require 'vendor/autoload.php';
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Methods: PUT");
define('SECRET', "gwFFL67IOoJuBqE4COZFHZg69UavVVne");
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$request = new Request();

$file = json_decode(file_get_contents("./Endpoints.json"), true);
$db = Database::DatabaseFactory();

$rank = "null";

$auth = Auth::AuthFactory($db, $request);
if(isset($auth)){
    if($auth->authenticate()){
        $rank = $auth->getRank();
    }else{
        $r = new \Response(401);
        $r->setBody(array(
            "status" => "error",
            "message" => "Invalid Credentials."
        ));
        exit();
    }
}

$endpoint = null;
foreach(array_keys($file[$rank]) as $route){
    if (preg_match($route, $request->getURI())){
        $endpoint = $route;
    }
}

if($endpoint == null){
    $r = new \Response(401);
    $r->setBody(array(
        "status" => "error",
        "message" => "Invalid Credentials."
    ));
    exit;
}

if(!in_array($request->getHTTPMethod(), $file[$rank][$endpoint]["methods"])){
    new Response(403);
    exit;
}

$resource = array_filter(explode('/', $endpoint));

$class = $file[$rank][$endpoint]["className"];

if($request->getPARAMETERS()){
    foreach(array_keys($request->getPARAMETERS()) as $param){
        $allowedParams = $file[$rank][$endpoint]["parameters"];
        if(!in_array($param, $allowedParams)){
            new Response(400);
            exit();
        }
    }
}

$endpointHandler = null;
if(!class_exists($class)){
    new Response(500);
} else $endpointHandler = new $class($db, $request);

switch($request->getHTTPMethod()){
    case 'GET':
        $result = $endpointHandler->GET();
        if(empty($result)){
            new Response(404);
            exit();
        }
        $response = new Response(200);
        $response->setHeader("Access-Control-Allow-Headers: *");
        $response->setHeader("Access-Control-Allow-Origin: *");
        $response->setBody($result);
        $response->sendResponse();
        break;

    case 'POST':
        $result = $endpointHandler->POST();
        if(is_numeric($result)){
            new Response($result);
        }else{
            $response = new Response(200);
            $response->setBody($result);
            $response->setHeader("Access-Control-Allow-Headers: *");
            $response->setHeader("Access-Control-Allow-Origin: *");
            $response->sendResponse();
        }
        break;

    case 'PUT':
        if($result = $endpointHandler->PUT()){
            $response = new Response(200);
            $response->setHeader("Access-Control-Allow-Headers: *");
            $response->setHeader("Access-Control-Allow-Origin: *");
            $response->sendResponse();
        }
        break;
    case 'DELETE':
        if($result = $endpointHandler->DELETE()){
            new Response(200);
        }
        break;
    case 'OPTIONS':
        $response = new Response(200);
        $response->setHeader("Access-Control-Allow-Headers: *");
        $response->setHeader("Access-Control-Allow-Origin: *");
        $string = "";
        $count = 0;
        foreach($file as $rank){
            foreach($rank[$endpoint]["methods"] as $method){
                $count == 0 ? $string .= $method: $string .= ",".$method;
                $count++;
            }   
        }
        $response->setHeader("Access-Control-Allow-Methods:".$string);
        exit();
        break;
    default:
        break;
}
