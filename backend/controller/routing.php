
<?php
include "../model/user.php";

header("Content-Type: application/json");

$methods = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');

$basePath = "/backend/controller/routing.php/";
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

$param=empty($_GET) ? (empty($_POST) ?  json_decode(file_get_contents('php://input'), true) : $_POST  ) : $_GET;

//use est un capture dans ce context c'est inutile de l'utiliser mais j'aime  comment sa ressemble
$action = function($data) use($methods,$uri){
  
    $route = "$methods/$uri";
    
    switch($route){
        //route user
        case "POST/user/add": return createUser($data);
        case "PATCH/user/update": return updateUser($data);
        case "GET/user/fetchAll": return fetchAllUser();
        case "GET/user/findOne": return findOneUser($data);
        case "DELETE/user/delete":  return deleteUser($data);
        case "GET/user/login": return login($data);
        
        //route crypto
        case "POST/crypto/add": return createCrypto($data);
        case "PATCH/crypto/update": return updateCrypto($data);
        case "GET/crypto/fetchAll": return fetchAllCrypto();
        case "GET/crypto/findOne": return findOneCrypto($data);
        case "DELETE/crypto/delete": return deleteCrypto($data);
        

        default: {http_response_code(404); return ["erreur, route non-existante"];}//bad request;
    }
};

echo json_encode($action($param));
?>

