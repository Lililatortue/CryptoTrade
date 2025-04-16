<?php

function Historiquefetch($data, $taille_transaction=10, $result = false){
    $db = DatabaseConnection::getInstance();
    try{
        $stmt = $db->getConnection() -> prepare("SELECT * FROM historique WHERE crypto_name = :crypto_name
                                                    ORDER BY h.update_time DESC LIMIT :taille_transaction");
        $stmt -> execute([":crypto_name"=>$data,
                                  ":taille_transaction"=>$taille_transaction]);
        $crypto=$stmt->fetchAll(PDO::FETCH_ASSOC);                          
    } catch(Exception $e){
        if($result === true){
            http_response_code(500);
            echo json_encode(["DBError"=>"erreur logique  transaction."]);
        }
        return;
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
            echo json_encode(["error"=>"un erreur est survenu durant l'enregistrement de la transaction"]);
        }
        return;
    }

}

function HistoriqueAdd($db, $data){

    $stmt = $db->prepare("INSERT INTO historique (crypto_name, closing_price)
                          VALUES (:crypto_name, :price_usd)");
    return $stmt->execute([
        ':crypto_name' => $data['name'],
        ':price_usd' => $data['price_usd']
    ]);

}

?>