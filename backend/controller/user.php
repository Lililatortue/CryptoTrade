
<?php
include "../dbdata/dBConnection.php";
include "../dbdata/config.php"; 

function findOneUser($data,$return=true){
    $db = DatabaseConnection::getInstance();
    try{   
        $stmt = $db->getConnection() -> prepare( "SELECT * FROM utilisateur WHERE email=:email" );
        $stmt -> execute([":email"=>$data['email']]);
        $user=$stmt->fetch(PDO::FETCH_ASSOC);
  
    } catch (Exception $e) {
        //error handling server error
        http_response_code(500);
        header("Content-Type: application/json");
        echo json_encode(["error"=>"logical erreur findOneUser"]);
        return;
    }
    if($user){
        http_response_code(200);
        if($return === true)
            echo json_encode($user);
        return $user;
    } else {
        http_response_code(400);
        echo json_encode(["badRequest"=>"no data found"]) ;
        return;
    }
}

function fetchAllUser(){      
    $db = DatabaseConnection::getInstance();
    try{
        $stmt = $db->getConnection() -> prepare( "SELECT * FROM utilisateur");
        $stmt -> execute();
        //sa retourne une list de user
        $users=$stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as &$user) {
            unset($user['id']);
            unset($user['salt']);
            unset($user['password']);
        }
    } catch(PDOException $e){
        http_response_code(500);
        header("Content-Type: application/json");
        echo json_encode(["error"=>"logical erreur fetchAllUser"]);
        return;
    }
    if(!empty($users)){
        http_response_code(200);
        echo json_encode($users);
        return $users;
    } else {
        http_response_code(404);
        echo json_encode(["error"=>"no data found"]) ;
        return;
    }
}

function createUser($data){
    $db = DatabaseConnection::getInstance(); 
    try{
        //salt and hash password
        $salt = base64_encode(random_bytes(16));
        $iterations = 100000;
        $hash = hash_pbkdf2("sha256",$data['password'],$salt,$iterations,20);

        $stmt = $db->getConnection() -> prepare("INSERT INTO utilisateur(username, email, pays, age,role, password, salt ) 
                                    VALUES (:username,:email, :pays, :age, 'guest', :password, :salt)");
        $bool=$stmt -> execute([":username" => $data['username'],
                                 ":email" => $data['email'], 
                                ":pays" =>$data['pays'],
                                ":age" => $data['age'],
                                ":password" =>$hash,
                                ":salt" =>$salt]);
    } catch(PDOException $e){
        http_response_code(400);
        if($e->getCode() == 23000){//duplicate key   
            header("Content-Type: application/json");
            echo json_encode(["badRequest"=>"email already exist"]);
            return;
        }
        
    }
    if($bool){
        http_response_code(200);
        echo json_encode(["user created succesfully."]);
        return $bool;
    } else {
        http_response_code(404);//204 est retourne quand la requete a reussi mais aucun contenu est retourner
        echo json_encode(["error"=>"erreur est survenu durant la creation."]);
        return;
    }
}

function updateUser($data){
    $db = DatabaseConnection::getInstance();
    //bad error handling
    try{
        $stmt = $db->getConnection() -> prepare(createString($data));
        $bool = $stmt -> execute(createExecute($data));
    } catch(Exception $e){
        http_response_code(500);
        echo json_encode(["erreur"=>"erreur logique  updateUser."]);
        return;
    }

    if($bool){
        http_response_code(200);
        echo json_encode("utilisateur changer avec succes.");
        return $bool;
    } else {
        http_response_code(400);
        echo json_encode(["erreur"=>"un erreur est survenu durant la mise a jour de l'utilisateur."]);
        return;
    }
}

function deleteUser($data){
    $db = DatabaseConnection::getInstance();
    try{
        $stmt = $db->getConnection() -> prepare("DELETE FROM utilisateur WHERE email=:email");
        $bool = $stmt -> execute(["id"=>$data['id']]);     
    } catch (Exeception $e) {
        http_response_code(500);
        echo json_encode(["erreur: erreur logique deleteUser."]);
        return;
    }
    if($bool){
        http_response_code(200);
        echo json_encode(["utilisateur efface avec succes"]);
        return $bool;
    } else {
        http_response_code(400);
        echo json_encode(["un erreur est survenu durant l'effacement de l'utilisateur."]);
        return;
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
