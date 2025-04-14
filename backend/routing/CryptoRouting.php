<?php
include "../controller/crypto.php";

function CryptoRouter($data,$route){
    $logger=$_SESSION['LOGGER_ACTION'];
    $observer = new endpointObserver();
    
    switch($route){ 
        case "POST/crypto/add": $logger->log(function() use ($data){
                                return createCrypto($data);},$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                exit;
                                  
        case "PATCH/crypto/update": $logger->log(function() use ($data){
                                    return updateCrypto($data);},$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                    exit;
        case "GET/crypto/fetchAll": $logger->log(function() use ($data){
                                    return fetchAllCrypto();},$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                    exit;
        case "GET/crypto/findOne":  $logger->log(function() use ($data){
                                    return findOneCrypto($data);},$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                    exit;
    }
}
?>