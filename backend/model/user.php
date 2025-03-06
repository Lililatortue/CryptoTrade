
<?php
include "../dbdata/dBConnection.php";
include "config.php"; 

function findOneUser($data){

    try{
        $db=DatabaseConnection::getInstance();
        $stmt = $db -> prepare( "SELECT * FROM user WHERE email=:email" );
        $stmt -> execute([":email"=>$data['email']]);
        $user=$stmt->fetch(PDO::FETCH_ASSOC);
        
        
    } catch (Exception $e) {
        //error handling server error
        http_response_code(500);
        return ["erreur: logical erreur findOneUser"];
    }
    if($user){
        http_response_code(200);
        return $user;
    } else {
        http_response_code(204);//204 est retourne quand la requete a reussi mais aucun contenu est retourner
        return ["no data found"];
    }
}

function fetchAllUser(){      
    try{
        $db= DatabaseConnection::getInstance();
        $stmt=$db -> prepare( "SELECT * FROM user");
        $stmt -> execute();
        //sa retourne une list de user
        $users=$stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as &$user) {
            unset($user['salt']);
        }
    } catch(PDOException $e){
        http_response_code(500);
        return ["erreur: impossible d'etablir une connection avec la database."];
    }
    if($users){
        http_response_code(200);
        return ["user :"=>$users];
    } else {
        http_response_code(204);//204 est retourne quand la requete a reussi mais aucun contenu est retourner
        return ["no data found"];
    }
}

function createUser($data){
    
    try{
        $db= DatabaseConnection::getInstance();
        //salt and hash password
        $salt = base64_encode(random_bytes(16));
        $iterations = 600000;
        $hash = hash_pbkdf2("sha256",$data['password'],$salt,$iterations,20);

        $stmt = $db -> prepare("INSERT INTO user(username, email, pays, age, password, salt ) 
                                    VALUES (:username,:email, :pays, :age, :password, :salt)");
        $bool=$stmt -> execute([":username" => $data['username'],
                                 ":email" => $data['email'], 
                                ":pays" =>$data['pays'],
                                ":age" => $data['age'],
                                ":password" =>$hash,
                                ":salt" =>$salt]);
    } catch(Exception $e){
        //error handling server error
        http_response_code(500);
        return ["erreur: erreur logique create.".$e->getMessage()];
    }
    if($bool){
        http_response_code(200);
        return ["user created succesfully."];
    } else {
        http_response_code(400);//204 est retourne quand la requete a reussi mais aucun contenu est retourner
        return ["erreur est survenu durant la creation."];
    }
}

function updateUser($data){
    //bad error handling
    try{
        $db= DatabaseConnection::getInstance(); 
        $stmt = $db -> prepare(createString($data));
        $bool = $stmt -> execute(createExecute($data));
    } catch(Exception $e){
        http_response_code(500);
        return ["erreur: erreur logique  updateUser."];
    }

    if($bool){
        http_response_code(200);
        return ["utilisateur changer avec succes."];
    } else {
        http_response_code(400);
        return ["un erreur est survenu durant la mise a jour de l'utilisateur."];
    }
}

function deleteUser($data){
    $db= DatabaseConnection::getInstance();
    try{
        $stmt = $db -> prepare("DELETE FROM user WHERE email=:email");
        $bool = $stmt -> execute(["id"=>$data['id']]);     
    } catch (Exeception $e) {
        http_response_code(500);
        return ["erreur: erreur logique deleteUser."];
    }
    if($bool){
        http_response_code(200);
        return ["utilisateur efface avec succes"];
    } else {
        http_response_code(400);
        return ["un erreur est survenu durant l'effacement de l'utilisateur."];
    }
}

function login($data){
    $user=findOneUser($data);
    
    $iterations = 600000;
    $hash = hash_pbkdf2("sha256",$data['password'],$user["salt"],$iterations,20);
    
    if($hash==$user['password']){
        http_response_code(200);
        return $user;
    } else {
        http_response_code(401);
        return [("error : invalid credentials")];
    }
}

function createString($data){
    $usedData=$data;
    unset($usedData['email']);
    $columns = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
    
    return "UPDATE user SET $columns WHERE email = :email";
}
function createExecute($data){
    $query=[];
    foreach($data as $key=>$value){
        $query[":".$key]=$value;
    }
    return $query;
}
?>
