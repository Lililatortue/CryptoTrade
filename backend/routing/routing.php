<?php
include "../controller/controllerUser.php";
include "../controller/controllerWallet.php";
include "../controller/controllerCrypto.php";
include "../controller/controllerSession.php";
include "../controller/controllerTransaction.php";
include "../sanitizer/endpointObserver.php";
include "../sanitizer/logger.php";


//variable global
session_start();
if(!isset($_SESSION['LOGGER_ACTION'])){
  $_SESSION['LOGGER_ACTION']=logger::getLoggerInstance('APICALLS','../sanitizer/logs/',logType::ACTION );
}

//----------------------------Helper functions-----------------------
function getRouter($uri){
  $type='';
  for($i=0; $uri[$i] != '/' && $i<strlen($uri) ;$i++ ){
    $type[$i] = $uri[$i];
  }
 return $type;
}
//-------------------------------------------------------------------


$logger=$_SESSION['LOGGER_ACTION'];
//variable init
$observer = new endpointObserver();

// ROUTE CREATION
header("Content-Type: application/json"); 

$methods = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');

$basePath = "/backend/routing/routing.php/";
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}
$param=empty($_GET) ? (empty($_POST) ?  json_decode(file_get_contents('php://input'), true) : $_POST  ) : $_GET;

//use est un capture dans ce context c'est inutile de l'utiliser mais j'aime  comment sa ressemble
$action = function($data) use($methods,$uri,$observer){
    $route = "$methods/$uri";
    switch(getRouter($uri)){

      //route user management
        case "user" : return userController($data,$route);break;
      
      //session management
        case "session": return sessionController($data,$route);break;

      //route crypto management
        case "crypto":return cryptoController($data,$route);break;
                                  
      //route portefeuille management
        case "wallet": return walletController($data,$route);break;

      //route statistique management
       case "transaction":return transactionController($data,$route);break;

      //route statistique management
        case "statistique":return statistiqueController($data,$route);break;
        
        default: {http_response_code(404); return ["erreur, route non-existante"];}//bad request;
    }
};
//sanitize data
$observer->sanitize($param);
echo json_encode($action($param));
?>

