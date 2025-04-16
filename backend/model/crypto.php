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
            echo json_encode(["erreur" => "erreur logique  createCrypto."]);
            return;
        }
        if($bool){
            http_response_code(200);
            echo json_encode(["succes: crypto succesfully created."]);
            return $bool;
        } else {
            http_response_code(400);
            echo json_encode(["erreur"=>"un erreur est survenu durant la creation du crypto."]);
            return;
        }
    }

    function updatePriceCrypto($db,$data){
        $stmt = $db->prepare("UPDATE crypto SET price_usd = :price_usd WHERE name = :name");
        return $stmt->execute([
            ':price_usd' => $data['price_usd'],
            ':name' => $data['name']
        ]);
     }

    function fetchAllCrypto($result=false){
        $db=DatabaseConnection::getInstance();

        try{
            $stmt = $db->getConnection()->prepare("SELECT * FROM crypto");
            $stmt->execute();
            $crypto=$stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e){
            if($result === true){
                http_response_code(500);
                echo json_encode(["DBError" => "erreur logique  fetchAllCrypto."]);
            }
            return;
        }
        if(!empty($crypto)){
            if($result === true){
                http_response_code(200);
                echo json_encode($crypto);
            }
            return $crypto;
        } else {
            if($result === true){
                http_response_code(400);
                echo json_encode(["error" =>" list de crypto vide"]);
            }
            return;
        }

    }

    function findOneCrypto($data, $result = false){
        $db=DatabaseConnection::getInstance();
        $token = validateToken();       
        if(empty($token)){
            http_response_code(401);
            echo json_encode(["InvalidCredential" => "Invalid credential"]);
            return;
        }
        
        try{
            $stmt = $db->getConnection()->prepare("SELECT * FROM crypto WHERE name = :crypto_name");
            $stmt -> execute([":crypto_name"=>$data['crypto_name']]);
            $crypto=$stmt->fetch(PDO::FETCH_ASSOC);
        } catch(Exception $e){
            if($result === true){
                http_response_code(500);
                echo json_encode(["DBError: erreur logique findOneCrypto."]);
            }
            return false;
        }
        if($crypto){
            if($result === true){
                http_response_code(200);
                echo json_encode($crypto);
            }
            return $crypto;
        } else {
            if($result === true){
                http_response_code(400);
                echo json_encode(["error"=>"Un erreur est survenu durant la recherche des cryptos"]);
            }
            return false;
        }
    }

?>