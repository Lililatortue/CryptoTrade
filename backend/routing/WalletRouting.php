<?php
include "../controller/wallet.php";
function WalletRouter($data,$route){
  $logger=$_SESSION['LOGGER_ACTION'];
  $observer = new endpointObserver();
    switch($route){
        //route user            
        case "POST/wallet/acheter":$observer->userInfoPolicies($data);      
                                 $logger->log(function() use ($data){
                                  return walletAcheter($data);},$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                 exit; 

        case "PATCH/wallet/sell":$observer->userInfoPolicies($data);
                                   $logger->log(function() use ($data){
                                   return walletSell($data);},$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                   exit; 

        case "GET/wallet/findOne":$logger->log(function() use ($data){
                                  return walletFindOne($data);},$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                  exit; 
    }
}
?>