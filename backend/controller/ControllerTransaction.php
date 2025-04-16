<?php
include_once "../model/transaction.php";

function transactionController($data,$route){
    $logger=$_SESSION['LOGGER_ACTION'];
    $observer = new endpointObserver();

    switch($route){
        case "GET/transaction/findOne":
                                   $logger->log(function()  {
                                   return transactionFindOne();}  ,$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                   exit;

        case "GET/transaction/fetchAll":$logger->log(function()  {
                                         return transactionFetchAll();}, $_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                         exit;
    }
}
?>