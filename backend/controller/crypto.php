<?php


    function createCrypto($data){
        $db=DatabaseConnection::getInstance();
        $token = validateToken();       
        if(!$token['role'] == 'admin' || empty($token)){
            http_response_code(401);
            return [("error: invalid credential")];
        }
            
        try{
            $stmt = $db->getConnection()->prepare("INSERT INTO crypto(crypto_name, quantite, valeur, image)
                                                   VALUE(:crypto_name, :quantite, :valeur, :image)");
            $bool=$stmt->execute([':crypto_name'=>$data['crypto_name'],
                            ':quantite'   =>$data['quantite'],
                            ':valeur'     =>$data['valeur'],
                            ':image'      =>$data['image']]);
        } catch(Exception $e){
            http_response_code(500);
            return ["erreur: erreur logique  createCrypto."];
        }
        if($bool){
            http_response_code(200);
            return [("succes: crypto succesfully created.")];
        } else {
            http_response_code(400);
            return ["un erreur est survenu durant la creation du crypto."];
        }
    }

    // function updateCrypto(){
    //     $db = DatabaseConnection::getInstance();
    //     //bad error handling
    //     try{
    //         $stmt = $db->getConnection() -> prepare(createString($data));
    //         $bool = $stmt -> execute(createExecute($data));
    //     } catch(Exception $e){
    //         http_response_code(500);
    //         return ["erreur: erreur logique  updateCrypto."];
    //     }
    
    //     if($bool){
    //         http_response_code(200);
    //         return ["utilisatcryptoeur changer avec succes."];
    //     } else {
    //         http_response_code(400);
    //         return ["un erreur est survenu durant la mise a jours du crypto."];
    //     }
    // }

    function fetchAllCrypto(){
        $db=DatabaseConnection::getInstance();

        try{
            $stmt = $db->getConnection()->prepare("SELECT * FROM crypto");
            $stmt->execute();
            $crypto=$stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e){
            http_response_code(500);
            echo json_encode(["erreur" => "erreur logique  fetchAllCrypto."]);
            exit;
        }
        if(!empty($crypto)){
            http_response_code(200);
            echo json_encode($crypto);
            exit;
        } else {
            http_response_code(400);
            echo json_encode(["erreur" =>" list de crypto vide"]);
            exit;
        }

    }

    function findOneCrypto($data){
        $db=DatabaseConnection::getInstance();
        $token = validateToken();       
        if(empty($token)){
            http_response_code(401);
            return [("error: invalid credential")];
        }
        
        try{
            $stmt = $db->getConnection()->prepare("SELECT * FROM crypto WHERE crypto_name = :crypto_name");
            $stmt -> execute([":crypto_name"=>$data['crypto_name']]);
            $crypto=$stmt->fetch(PDO::FETCH_ASSOC);
        } catch(Exception $e){
            http_response_code(500);
            echo json_encode(["erreur: erreur logique findOneCrypto."]);
            exit;
        }
        if($crypto){
            http_response_code(200);
            echo json_encode(["crypto"=>$crypto]);
            exit;
        } else {
            http_response_code(400);
            echo json_encode(["error"=>"Un erreur est survenu durant la recherche des cryptos"]);
            exit;
        }
    }

?>