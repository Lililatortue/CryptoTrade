<?php
require_once "../vendor/autoload.php";

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
}

function login($data){
    $user=findOneUser($data,false);
    $iterations = 100000;
    $hash = hash_pbkdf2("sha256",$data['password'],$user["salt"],$iterations,20);
    
    if($hash==$user['password']){
        http_response_code(200);
        MakeToken($user);

        return ;
    } else {
        http_response_code(401);
        echo json_encode(["error" => "invalid credentials"]);
        return;
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
       

    if (empty($jwt)) {
        http_response_code(401);
        echo json_encode(['error' => 'aucun token']);
        return;
    }

    $key = 'clee_super_secrete';
    try{
        $decoded = JWT::decode($jwt,new Key($key,'HS256'));
        http_response_code(200);
        echo json_encode($decoded);
        return $decoded;
    } catch(Exception $e){
        ob_clean(); 
        http_response_code(401);
        echo json_encode(['error'=>"Unauthorize"]);
        return;
    }
    
}

?>