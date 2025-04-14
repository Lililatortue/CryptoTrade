<?php
include "../dbdata/dBConnection.php";
include "../dbdata/config.php"; 

function walletAcheter($data,$return=true){
    $db = DatabaseConnection::getInstance();
    $token = validateToken();       
        if(empty($token)){
            http_response_code(401);
            echo json_encode(["InvalidCredential" => "Invalid credential"]);
            return;
        }
    try{   
        //TODO::inclure strip
        $wallet = walletFindOne($data,false);
        $crypto = cryptoFindOne($data,false);
        //voir si le crypto est deja detenu
        foreach($wallet as $key=>$cryptowallet){
           if( $cryptowallet['crypto_id']==$crypto['id']){
                $d['crypto_id']=$crypto['id'];
                $d['user_id']=$cryptowallet['id'];
                $d['qte']=$cryptowallet['amount']+$data['qtebuy'];
                $bool=walletUpdate($d);
                return $bool;
            }
        }
        $bool=walletAdd($data);
        return $bool;
    } catch (Exception $e) {
        //error handling server error
        http_response_code(500);
        header("Content-Type: application/json");
        echo json_encode(["error"=>"logical erreur findOneUser"]);
        return;
    }
}

function walletFindOne($data,$return=true){      
    $db = DatabaseConnection::getInstance();
    $token = validateToken();       
        if(empty($token)){
            http_response_code(401);
            echo json_encode(["InvalidCredential" => "Invalid credential"]);
            return;
        }
    try{
        $user=findOneUser($data);
        if(empty($user)){
            http_response_code(400);
            echo json_encode(["badRequest"=>"no data found"]) ;
        }
        $stmt=$db->getConnection()->prepare("SELECT * FROM portefeuille WHERE user_id=:user_i");
        $stmt->execute(["user_id"=> $user['id']]);

        $wallet=$stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e){
        http_response_code(500);
        header("Content-Type: application/json");
        echo json_encode(["error"=>"logical erreur fetchAllUser"]);
        return;
    }
    if(!empty($wallet)){
        http_response_code(200);
        if($return == true)
            echo json_encode($wallet);
        return $wallet;
    } else {
        http_response_code(404);
        echo json_encode(["error"=>"no data found"]) ;
        return;
    }
}


function walletUpdate($data){
    $db = DatabaseConnection::getInstance();
    //bad error handling
    try{
        $stmt = $db->getConnection() -> prepare("UPDATE portefeuille SET (quantite = :quantite) 
                                                WHERE user_id=:user_id AND crypto_id = :crypto_id");
        $bool = $stmt -> execute([":quantite"=>$data['qte'],
                                  ":user_id"=>$data["user_id"],
                                  ":crypto_id"=>$data["crypto_id"]]);
    } catch(Exception $e){
        http_response_code(500);
        echo json_encode(["erreur"=>"erreur logique  walletUpdate."]);
        return;
    }

    if($bool){
        http_response_code(200);
        echo json_encode("montant ajouter");
        return $bool;
    } else {
        http_response_code(400);
        echo json_encode(["erreur"=>"un erreur est survenu durant la mise a jour du portefeuille"]);
        return;
    }
}

function walletAdd($data){
    $db = DatabaseConnection::getInstance();
    //bad error handling
    try{//TODO demander achat data sert a quoi
        $stmt = $db->getConnection() -> prepare(" INSERT INTO portefeuille (user_id, crypto_id, quantite)
                                                  VALUES (:user_id, :crypto_id, :quantite)");
        $bool = $stmt -> execute([":quantite"=>$data['qte'],
                                  ":user_id"=>$data["user_id"],
                                  ":crypto_id"=>$data["crypto_id"]]);
    } catch(Exception $e){
        http_response_code(500);
        echo json_encode(["erreur"=>"erreur logique  walletUpdate."]);
        return;
    }

    if($bool){
        http_response_code(200);
        echo json_encode("crypto acheter ajouter");
        return $bool;
    } else {
        http_response_code(400);
        echo json_encode(["erreur"=>"un erreur est survenu durant la mise a jour du portefeuille"]);
        return;
    }
}

function walletSell($data){
    $db = DatabaseConnection::getInstance();
    $token = validateToken();       
        if(empty($token)){
            http_response_code(401);
            echo json_encode(["InvalidCredential" => "Invalid credential"]);
            return;
        }
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
?>
