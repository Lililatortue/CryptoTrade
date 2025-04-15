<?php
require_once "../dbdata/dBConnection.php";
require_once "../dbdata/config.php"; 
/*data requis est le email,
  le nom du crypto 
  la quantite acheter

*/
function walletFindOne($data,$return=true){      
    $db = DatabaseConnection::getInstance();
    // $token = validateToken();       
    //     if(empty($token)){
    //         http_response_code(401);
    //         echo json_encode(["InvalidCredential" => "Invalid credential"]);
    //         return;
    //     }
    try{
        $stmt=$db->getConnection()->prepare("SELECT * FROM portefeuille WHERE user_email=:email");
        $stmt->execute([":email"=> $data['email']]);

        $wallet=$stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e){
        
        if($return === true){
            http_response_code(500);
            header("Content-Type: application/json");
            echo json_encode(["DBError"=>"logical erreur wallet"]);
        }
        return false;
    }
    if(!empty($wallet)){   
        if($return === true){
            http_response_code(200);
            echo json_encode($wallet);
        }     
        return $wallet;
    } else {
        if($return === true){
            http_response_code(404);
            echo json_encode(["noDataFound"=>"no data found"]) ;
        }   
        return false;
    }
}


function walletUpdate($data){
    $db = DatabaseConnection::getInstance();
    //bad error handling
    try{
        $stmt = $db->getConnection() -> prepare("UPDATE portefeuille SET quantite = :quantite 
                                                WHERE user_email=:user_email AND crypto_name = :crypto_name");
        $bool = $stmt -> execute([":user_email"=>$data["email"],
                                  ":crypto_name"=>$data["crypto_name"],
                                  ":quantite"=>$data['qte']]);
        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode(["error" => "Aucune correspondance trouvée pour la mise à jour"]);
            return;
        }
    } catch(Exception $e){
        http_response_code(500);
        echo json_encode(["DBError"=>"erreur logique  walletUpdate."]);
        return;
    }

    if($bool){
        http_response_code(200);
        echo json_encode(["succes"=>"montant ajouter"]);
        return $bool;
    } else {
        http_response_code(400);
        echo json_encode(["error"=>"un erreur est survenu durant la mise a jour du portefeuille"]);
        return;
    }
}

function walletAdd($data){
    $db = DatabaseConnection::getInstance();

    try{
        $stmt = $db->getConnection() -> prepare(" INSERT INTO portefeuille (user_email, crypto_name, quantite)
                                                  VALUES (:user_email, :crypto_name, :quantite)");
        $bool = $stmt -> execute([":user_email"=>$data["email"],
                                  ":crypto_name"=>$data["crypto_name"],
                                  ":quantite"=>$data['qte']]);
    } catch(Exception $e){
        http_response_code(500);
        echo $e->getMessage();
        echo json_encode(["DBError"=>"erreur logique  walletAdd."]);
        return;
    }

    if($bool){
        http_response_code(200);
        echo json_encode("crypto acheter ajouter");
        return $bool;
    } else {
        http_response_code(400);
        echo json_encode(["error"=>"un erreur est survenu durant l'ajout de portefeuille"]);
        return;
    }
}
function walletDelete($data){
    $db = DatabaseConnection::getInstance();

    try{
        $stmt = $db->getConnection() -> prepare(" DELETE FROM portefeuille 
                                        WHERE user_email = :user_email && crypto_name =:crypto_name");
        $bool = $stmt -> execute([":user_email"=>$data["email"],
                                  ":crypto_name"=>$data["crypto_name"],]);
        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode(["error" => "Aucune correspondance trouvée pour la effacement à jour"]);
            return;
        }

    } catch(Exception $e){
        http_response_code(500);
        echo $e->getMessage();
        echo json_encode(["DBError"=>"erreur logique  walletAdd."]);
        return;
    }

    if($bool){
        http_response_code(200);
        echo json_encode("crypto acheter ajouter");
        return $bool;
    } else {
        http_response_code(400);
        echo json_encode(["error"=>"un erreur est survenu durant l'ajout de portefeuille"]);
        return;
    }
}

?>
