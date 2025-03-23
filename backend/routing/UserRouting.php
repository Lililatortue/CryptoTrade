
<?php
include "../controller/user.php";
function UserRouter($data,$route){
  $logger=$_SESSION['LOGGER_ACTION'];
  $observer = new endpointObserver();
  $logger->setFalse();
    switch($route){
        //route user            
        case "POST/user/add":   $logger->startTimer();
                                  if(empty($resultat=$observer->userInfoPolicies($data))){
                                    $resultat=createUser($data); 
                                    (http_response_code()==200) ? $logger ->setTrue(): $logger->setFalse();
                                  }
                                $logger->EndTimer();
                                $logger->logging($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route); 
                                return $resultat;

        case "PATCH/user/update":$logger->startTimer();
                                    if(empty($resultat=$observer->userInfoPolicies($data))){
                                      $resultat=updateUser($data);
                                      (http_response_code()==200) ? $logger ->setTrue(): $logger->setFalse();
                                    }
                                $logger->EndTimer();  
                                $logger->logging($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route); 
                                return $resultat;

        case "GET/user/fetchAll":$logger->startTimer();
                                    $resultat=fetchAllUser(); 
                                    (http_response_code()==200) ? $logger ->setTrue(): $logger->setFalse();
                                $logger->EndTimer();      
                                $logger->logging($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                return $resultat;

        case "GET/user/findOne":
                                $logger->startTimer();   
                                    $resultat=findOneUser($data);
                                    (http_response_code()==200) ? $logger ->setTrue(): $logger->setFalse();
                                $logger->EndTimer(); 
                                $logger->logging($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route);
                                return $resultat;

        case "DELETE/user/delete":
                                $logger->startTimer(); 
                                $resultat=deleteUser($data);
                                (http_response_code()==200) ? $logger ->setTrue(): $logger->setFalse();
                                $logger->EndTimer(); 
                                $logger->logging($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$route,$resultat);
                                return $resultat;


    }
}
?>