<?php
include "../controller/session.php";



function SessionRouter($data,$route){
    $logger=$_SESSION['LOGGER_ACTION'];
    $observer = new endpointObserver();

    switch($route){
        case "POST/session/login":
                                   $logger->log(function() use ($data) {
                                   return login($data);}  ,$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                   exit;

        case "GET/session/validateToken":$logger->log(function() use ($data) {
                                         return validateToken();}, $_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                         exit;
    }
}
?>