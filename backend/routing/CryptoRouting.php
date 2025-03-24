<?php
include "../controller/crypto.php";

function CryptoRouter($data,$route){
    $logger=$_SESSION['LOGGER_ACTION'];
    $observer = new endpointObserver();
    $logger->setFalse();
    
    switch($route){ 
        case "POST/crypto/add":   $logger->startTimer();
                                  $resultat=createCrypto($data);
                                  (http_response_code()==200) ? $logger ->setTrue(): $logger->setFalse();
                                  $logger->EndTimer();
                                  $logger->logging($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route,$resultat);
                                  return $resultat;
                                  
        case "PATCH/crypto/update": return updateCrypto($data);
        case "GET/crypto/fetchAll": return fetchAllCrypto();
        case "GET/crypto/findOne": return findOneCrypto($data);
    }
}
?>