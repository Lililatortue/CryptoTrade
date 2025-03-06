
<?php
include "../model/user.php";
include "../sanitizer/endpointObserver.php";
include "../sanitizer/logger.php";


//variable init
$observer = new endpointObserver();

// ROUTE CREATION
header("Content-Type: application/json"); 

$methods = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');

$basePath = "/backend/controller/routing.php/";
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}
$param=empty($_GET) ? (empty($_POST) ?  json_decode(file_get_contents('php://input'), true) : $_POST  ) : $_GET;

//use est un capture dans ce context c'est inutile de l'utiliser mais j'aime  comment sa ressemble
$action = function($data) use($methods,$uri,$observer){
  
    $route = "$methods/$uri";
    $logger = new logger('APICALLS','../sanitizer/logs/',logType::ACTION );
    $logger ->setFalse();
    switch($route){
        //route user            
        case "POST/user/add":   $logger->startTimer();
                                if(empty($resultat=$observer->userInfoPolicies($data))){
                                  $resultat=createUser($data); 
                                  $logger ->setTrue();
                                }
                                $logger->EndTimer();
                                $logger->logging($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route,$resultat,$data); 
                                return $resultat;
        case "PATCH/user/update":$logger->startTimer();
                                if(empty($resultat=$observer->userInfoPolicies($data))){
                                  $resultat=updateUser($data);
                                  $logger->setTrue();
                                }
                                $logger->EndTimer();  
                                $logger->logging($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route,$resultat,$data); 
                                return $resultat;
        case "GET/user/fetchAll":$logger->startTimer();
                                $resultat=fetchAllUser(); 
                                $logger->EndTimer();          
                                $logger->logging($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route,$resultat,$data);
                                return $resultat;
        case "GET/user/findOne":$logger->startTimer();   
                                $resultat=findOneUser($data);
                                $logger->EndTimer(); 
                                $logger->logging($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route,$resultat,$data);
                                return findOneUser($data);
        case "DELETE/user/delete":$logger->startTimer(); 
                                $resultat=deleteUser($data);
                                $logger->EndTimer(); 
                                $logger->logging($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route,$resultat,$data);
                                return $resultat;
        case "GET/user/login":  $logger->startTimer();                           
                                $resultat=login($data);
                                $logger->EndTimer();
                                $logger->logging($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route,$resultat,$data);
                                return $resultat;
        
        //route crypto
        case "POST/crypto/add": return createCrypto($data);
        case "PATCH/crypto/update": return updateCrypto($data);
        case "GET/crypto/fetchAll": return fetchAllCrypto();
        case "GET/crypto/findOne": return findOneCrypto($data);
        case "DELETE/crypto/delete": return deleteCrypto($data);
        
        default: {http_response_code(404); return ["erreur, route non-existante"];}//bad request;
    }
};
//sanitize data
$observer->sanitize($param);
echo json_encode($action($param));
?>

