<?php
//require_once "session.php";

    function createCrypto($data){
        $db=DatabaseConnection::getInstance();
            
        try{
            $stmt = $db->getConnection()->prepare("INSERT INTO crypto(name, symbole, price_usd,
                                                         quantity, total_supply, market_cap, created_date);
                                                   VALUE(:name, :symbole, :price_usd, :quantity, :total_supply,
                                                            :market_cap,:created_date)");
            $bool=$stmt->execute([':name'=>$data['name'],
                            ':symbole'   =>$data['symbole'],
                            ':price_usd'     =>$data['price_usd'],
                            ':quantity'      =>$data['quantity'],
                            ':total_supply'   =>$data['total_supply'],
                            ':market_cap'     =>$data['market_cap'],
                            ':created_date'      =>$data['created_date'],]);
        } catch(Exception $e){
            http_response_code(500);
            json_encode(["erreur" => "erreur logique  createCrypto."]);
            return;
        }
        if($bool){
            http_response_code(200);
            json_encode(["succes: crypto succesfully created."]);
            return;
        } else {
            http_response_code(400);
            json_encode(["erreur"=>"un erreur est survenu durant la creation du crypto."]);
            return;
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
            return;
        }
        if(!empty($crypto)){
            http_response_code(200);
            echo json_encode($crypto);
            return $crypto;
        } else {
            http_response_code(400);
            echo json_encode(["erreur" =>" list de crypto vide"]);
            return;
        }

    }

    function findOneCrypto($data){
        $db=DatabaseConnection::getInstance();
        $token = validateToken();       
        if(empty($token)){
            http_response_code(401);
            echo json_encode(["InvalidCredential" => "Invalid credential"]);
            return;
        }
        
        try{
            $stmt = $db->getConnection()->prepare("SELECT * FROM crypto WHERE crypto_name = :crypto_name");
            $stmt -> execute([":crypto_name"=>$data['crypto_name']]);
            $crypto=$stmt->fetch(PDO::FETCH_ASSOC);
        } catch(Exception $e){
            http_response_code(500);
            echo json_encode(["erreur: erreur logique findOneCrypto."]);
            return;
        }
        if($crypto){
            http_response_code(200);
            echo json_encode($crypto);
            return $crypto;
        } else {
            http_response_code(400);
            echo json_encode(["error"=>"Un erreur est survenu durant la recherche des cryptos"]);
            return;
        }
    }

?>