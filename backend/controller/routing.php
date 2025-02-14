
<?php
include "../model/user.php";
header("Content-Type: application/json");

$methods = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');

$basePath = "/backend/controller/routing.php";
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

$param=empty($_GET) ? (empty($_POST) ?  null : $_POST ) : $_GET;

//use est un capture dans ce context c'est inutile de l'utiliser mais j'aime  comment sa ressemble
$action = function($data) use($methods,$uri){
    $route = "$methods$uri";
    echo $route;
    switch($route){
        //route user
        case "POST/user/add": return createUser($data);
        case "POST/user/update": return updateUser($data);
        case "POST/user/fetchAll": return fetchAllUser();
        case "GET/user/findOne": return findOneUser($data);
        case "GET/user/delete":  return deleteUser($data);
        //route crypto

        default: {http_response_code(404); return ["erreur, route non-existante"];}//bad request;
    }
};

echo json_encode($action($param));
?>