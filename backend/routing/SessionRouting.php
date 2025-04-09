<?php
include "../controller/session.php";



function SessionRouter($data,$route){
    $logger=$_SESSION['LOGGER_ACTION'];
    $observer = new endpointObserver();
    $logger->setFalse();

    switch($route){
        case "POST/session/login":        $logger->startTimer();                           
                                         $resultat=login($data);
                                         (http_response_code()==200) ? $logger ->setTrue(): $logger->setFalse();
                                         $logger->EndTimer();
                                         $logger->logging($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route,$resultat);
                                         return $resultat;

        case "POST/session/validateToken":$logger->startTimer();
                                         $resultat=validateToken($data);
                                         (http_response_code()==200) ? $logger ->setTrue(): $logger->setFalse();
                                         $logger->EndTimer();
                                         $logger->logging($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route,$resultat);
                                         return $resultat;
    }
}
?>