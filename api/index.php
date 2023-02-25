<?php
require 'vendor/autoload.php';

$request = new Request();

$file = json_decode(file_get_contents("../db/Endpoints.json"), true);
$db = Database::DatabaseFactory();
// $auth = Auth::AuthFactory($request, $db);

// if($auth!=null){
//     $auth->authenticate() ? $rank = $auth->authorise() : $rank = null;
// }else $rank = "null";

$endpoint = null;

foreach(array_keys($file[$rank]) as $route){
    if (preg_match($route, $request->getURI())){
        $endpoint = $route;
    }
}

if($endpoint == null){
    new Request(400);
    exit;
}

if(!in_array($request->getHTTPMethod(), $file[$rank][$endpoint]["methods"])){
    new Request(403);
    exit;
}

$resource = array_filter(explode('/', $endpoint));

$class = $file[$rank][$endpoint]["className"];

$params = $request->getPARAMETERS();

$endpointHandler = null;
if(!class_exists($class)){
    $endpointHandler = new Endpoint($db, $request);
} else $endpointHandler = new $class($db, $request);

switch($request->getHTTPMethod()){
    case 'GET':
        $result = $endpointHandler->GET();
        $response = new Response(200, $request->getHEADERS()['Accept']);
        $response->setBody($result);
        $response->sendResponse();
        break;
    case 'POST':
        if($result = $endpointHandler->POST()){
            new Response(201);
        }
        break;
    case 'PUT':
        if($result = $endpointHandler->PUT()){
            new Response(200);
        }
        break;
    case 'DELETE':
        if($result = $endpointHandler->DELETE()){
            new Response(200);
        }
        break;
    case 'HEAD':
        break;
    default:
        break;
}