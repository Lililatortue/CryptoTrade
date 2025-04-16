<?php
include_once "../service/walletService.php";
function walletController($data,$route){
  $logger=$_SESSION['LOGGER_ACTION'];
  $observer = new endpointObserver();
  echo $route;
    switch($route){
        case "POST/wallet/buy":   $observer->userInfoPolicies($data);
                                  $logger->log(function() use ($data){
                                   return buy($data);},$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                   exit; 

        case "POST/wallet/sell":  $observer->userInfoPolicies($data);
                                  $logger->log(function() use ($data){
                                  return sell($data);},$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                  exit; 

        case "POST/wallet/findOne":  $observer->userInfoPolicies($data);
                                  $logger->log(function() use ($data){
                                  return findOneWalletInfo($data);},$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                  exit;
    }
}
?>