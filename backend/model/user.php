
<?php
include "../dbdata/dBConnection.php";
include "config.php"; 

function findOneUser($data){
    
    try{
        $db=DatabaseConnection::getInstance();
        $stmt = $db -> prepare( "SELECT * FROM user WHERE id=:id" );
        $stmt -> execute([":id"=>$data['id']]);
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
    } catch(PDOException $e){
        http_response_code(500);
        return ["erreur: impossible d'etablir une connection avec la database."];
    }
    if($users){
        http_response_code(200);
        return [$users];
    } else {
        http_response_code(204);//204 est retourne quand la requete a reussi mais aucun contenu est retourner
        return ["no data found"];
    }
}

function createUser($data){
    
    try{
        $db= DatabaseConnection::getInstance();
        $stmt = $db -> prepare("INSERT INTO user(username, email, pays, age) 
                                    VALUES (:username,:email, :pays, :age)");
        $bool=$stmt -> execute([":username" => $data['username'], ":email" => $data['email'], 
                                ":pays" =>$data['pays'],":age" => $data['age']]);
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
//a modifier car il faut toute les parametre pour quelle fonctionne
function updateUser($data){
    //bad error handling
    try{
        $db= DatabaseConnection::getInstance();
        $stmt = $db -> prepare("UPDATE user SET username=:username, email=:email, pays=:pays, age=:age WHERE id=:id");
        $bool = $stmt -> execute([":id" => $data["id"],
                                  ":username" => $data["username"], 
                                  ":email" => $data["email"],
                                  ":pays" => $data["pays"],
                                  ":age" => $data["age"]]);
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
        $stmt = $db -> prepare("DELETE FROM user WHERE id=:id");
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

?>