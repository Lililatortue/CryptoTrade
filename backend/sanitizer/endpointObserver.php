<?php
class endpointObserver{
    
    // fait en sort que le script logger.php est activer asynchroniquement
    

    function sanitize(&$info){
        foreach($info as $key => &$data){
            $data=htmlspecialchars($data);
        }
    }
    //fonction pour verifier que toute policies concernant l'info de l'utilisateur est pertinant
    function userInfoPolicies($info){
        $pattern=json_decode(file_get_contents("..\\sanitizer\\Policies\\user_info.json"), true);  
        $json_error=[];
        foreach($info as $key=>$data){
            switch($key){
                case 'username' :{
                    if(preg_match("/".$pattern['username']['regex']."/",$data)==0){
                        $json_error['username']=[$pattern['username']['error_message']];
                    }
                    break;
                }
                case 'password' :{ 
                    if(preg_match("/".$pattern['password']['regex']."/",$data)==0){
                        $json_error['password']=[$pattern['password']['error_message']];
                    }
                    break;
                }
                case 'email' :{
                    if(!filter_var($data,FILTER_VALIDATE_EMAIL)){
                        $json_error['email']=[$pattern['email']['error_message']];
                    }
                    break;
                }
                //pour associer age legal avec pays pour linstant juste une constante
                case 'age' :{
                    if(!$data>=$pattern['age']['legal']){
                        $json_error['age']=[$pattern['age']['error_message']];
                    }
                    break;
                }
                default:break;
            }
        
        }
        if($json_error !=null){
        
            echo json_encode($json_error);
            exit;
        }
    }
    //assure que le niveaux dacces est adequoit pour faire cette action
    function AccesPolicies($info){
        $pattern=json_decode(file_get_contents("..\\sanitizer\\Policies\\acces_policies.json"), true); 
        $json_error=[]; 

        
    }

}

?>