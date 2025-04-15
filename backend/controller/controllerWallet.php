<?php
include_once "../service/walletService.php";
include_once "../model/wallet.php";
function WalletRouter($data,$route){
  $logger=$_SESSION['LOGGER_ACTION'];
  $observer = new endpointObserver();
  echo $route;
    switch($route){
        //route user            
        case "POST/wallet/add":  $observer->userInfoPolicies($data);      
                                 $logger->log(function() use ($data){
                                 return walletAdd($data);},$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                 exit; 

        case "PATCH/wallet/update":  $observer->userInfoPolicies($data);
                                   $logger->log(function() use ($data){
                                   return walletUpdate($data);},$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                   exit; 

        case "POST/wallet/findOne": $logger->log(function() use ($data){
                                   return walletFindOne($data);},$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                   exit; 
                                   

        case "POST/wallet/buy":   $observer->userInfoPolicies($data);
                                  $logger->log(function() use ($data){
                                   return buy($data);},$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                   exit; 

        case "POST/wallet/sell":  $observer->userInfoPolicies($data);
                                  $logger->log(function() use ($data){
                                  return sell($data);},$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                  exit; 
    }
}
?>