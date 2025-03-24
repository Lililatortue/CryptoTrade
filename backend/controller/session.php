<?php
require_once "../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function MakeToken($data){
    $key = 'clee_super_secrete';
    $payload=[
        'iat'     =>time(),
        'nbf'     =>time()+10,
        'exp'     =>time()+3600,  
        'username'=>$data['username'],
        'email'   =>$data['email'],
        'role'    =>$data['role'],
    ];
    $jwt = JWT::encode($payload,$key,'HS256');
    return (['token'=>$jwt]);
}

function login($data){
    $user=findOneUser($data);
    
    $iterations = 100000;
    $hash = hash_pbkdf2("sha256",$data['password'],$user["salt"],$iterations,20);
    
    if($hash==$user['password']){
        http_response_code(200);
        return MakeToken($user);
    } else {
        http_response_code(401);
        return [("error : invalid credentials")];
    }
}

function validateToken(){

    $header = apache_request_headers();
    if(isset($header['authorization']))
        $jwt = $header['authorization'];

    $key = 'clee_super_secrete';
    try{
        $decoded = JWT::decode($jwt,new Key($key,'HS256'));
        
        return(array) $decoded;
    } catch(Exception $e){
        http_response_code(401);
        return['error: invalid Token'];
    }
    
}

?>