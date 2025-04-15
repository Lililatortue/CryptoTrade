
<?php
include "../model/user.php";
function UserRouter($data,$route){
  $logger=$_SESSION['LOGGER_ACTION'];
  $observer = new endpointObserver();
    switch($route){
        //route user            
        case "POST/user/add":    $observer->userInfoPolicies($data);      
                                 $logger->log(function() use ($data) {
                                 return createUser($data);},$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                 exit; 

        case "PATCH/user/update":$observer->userInfoPolicies($data);
                                 $logger->log(function() use ($data) {
                                 return updateUser($data);},$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                 exit; 

        case "GET/user/fetchAll":$logger->log(function() {
                                 return fetchAllUser();} ,$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                 exit; 

        case "GET/user/findOne":$logger->log(function() use ($data) {
                                return findOneUser($data);}, $_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                exit; 

        case "DELETE/user/delete":$logger->log(function() use ($data) {
                                  return deleteUser($data);},$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                  exit; 



    }
}
?>