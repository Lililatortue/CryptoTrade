<?php
require_once "../../vendor/autoload.php"; //added '../'

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function MakeToken($data){
    $key = 'clee_super_secrete';
    $payload=[
        'iat'     =>time(),
        'nbf'     =>time(),
        'exp'     =>time()+3600,  
        'username'=>$data['username'],
        'email'   =>$data['email'],
        'role'    =>$data['role'],
    ];
    $jwt = JWT::encode($payload,$key,'HS256');
    http_response_code(200);
    echo json_encode(['token'=>$jwt]);
    exit;
}

function login($data){
    $user=findOneUser($data);

   
    $iterations = 100000;
    $hash = hash_pbkdf2("sha256",$data['password'],$user["salt"],$iterations,20);
    
    if($hash==$user['password']){
        http_response_code(200);
        MakeToken($user);
    } else {
        http_response_code(401);
        echo json_encode(["error : invalid credentials"]);
        exit;
    }
}

function validateToken(){

    $header = apache_request_headers();
    $jwt = null;
    if(isset($header['Authorization'])){
        $authHeader = $header['Authorization'];
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $jwt = $matches[1]; // Extract the token part
    }
    }
       

    if (!$jwt) {
        http_response_code(401);
        echo json_encode(['error' => 'aucun token']);
        exit;
    }

    $key = 'clee_super_secrete';
    try{
        $decoded = JWT::decode($jwt,new Key($key,'HS256'));
        http_response_code(200);
        echo json_encode($decoded);
        exit;
    } catch(Exception $e){
        ob_clean(); 
        http_response_code(401);
        echo json_encode(['error'=>"Unauthorize",'message' => $e->getMessage()]);
        exit;
    }
    
}

?>