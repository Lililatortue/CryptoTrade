<?php
function transactionAdd($data){
    $db = DatabaseConnection::getInstance();

    try{
        $stmt = $db->getConnection() -> prepare(" INSERT INTO transaction (user_email, crypto_name, quantite, montant, transaction_type)
                                                  VALUES (:user_email, :crypto_name, :quantite,:montant, :transaction_type)");
        $bool = $stmt -> execute([":user_email"=>$data["email"],
                                  ":crypto_name"=>$data["crypto_name"],
                                  ":quantite"=>$data['qte'],
                                  ":montant"=>$data["montant"],
                                  ":transaction_type"=>$data['transaction_type'],]);
    } catch(Exception $e){
        http_response_code(500);
        echo $e->getMessage();
        echo json_encode(["DBError"=>"erreur logique  transaction."]);
        return;
    }

    if($bool){
        http_response_code(200);
        return $bool;
    } else {
        http_response_code(400);
        echo json_encode(["error"=>"un erreur est survenu durant l'enregistrement de la transaction"]);
        return;
    }
}

function transactionFindOne(){
    $db = DatabaseConnection::getInstance();
    $token = validateToken(false);     
    if(empty($token)){
       http_response_code(401);
        echo json_encode(["InvalidCredential" => "Invalid credential"]);
        return;
    }

    try{
        $stmt = $db->getConnection() -> prepare("SELECT * FROM transaction WHERE user_email = :user_email");
        $stmt -> execute([":user_email"=>$token->email]);
        $transaction=$stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e){
        http_response_code(500);
        echo json_encode(["DBError"=>"erreur logique  transaction."]);
        return;
    }

    if($transaction){
        http_response_code(200);
        echo json_encode($transaction);
        return;
    } else {
        http_response_code(400);
        echo json_encode(["error"=>"un erreur est survenu durant l'enregistrement de la transaction"]);
        return;
    }
}

function transactionFetchAll(){
    $db = DatabaseConnection::getInstance();
    $token = validateToken(false);     
    if(empty($token) && $token->role != "admin"){
       http_response_code(401);
        echo json_encode(["InvalidCredential" => "Invalid credential"]);
        return;
    }
    try{
        $stmt = $db->getConnection() -> prepare("SELECT * FROM transaction");
        $stmt -> execute([]);
        $transaction=$stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e){
        http_response_code(500);
        echo json_encode(["DBError"=>"erreur logique  transaction."]);
        return;
    }

    if($transaction){
        http_response_code(200);
        echo json_encode($transaction);
        return;
    } else {
        http_response_code(400);
        echo json_encode(["error"=>"un erreur est survenu durant l'enregistrement de la transaction"]);
        return;
    }
}

?>