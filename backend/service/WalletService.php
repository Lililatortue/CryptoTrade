<?php
include_once "../model/wallet.php";
include_once "../model/transaction.php";
/*data requis est le email,
  le nom du crypto 
  la quantite acheter
*/
function buy($data,$return=true){
    $db = DatabaseConnection::getInstance();
    // $token = validateToken(false);       
    //     if(empty($token)){
    //         http_response_code(401);
    //         echo json_encode(["InvalidCredential" => "Invalid credential"]);
    //         return;
    //     }
    try{   
        //TODO::inclure strip

        $wallet = walletFindOne($data,false);
        $detiens = false;
        foreach($wallet as $crypto){
            if($crypto['crypto_name']==$data['crypto_name']){
                 $detiens= true;
                 $data['transaction_type']="buy";
                 $bool = transactionAdd($data);
                 $data['qte']+=$crypto['quantite'];
                break;
            }
        }
        if ($detiens) {
            $bool = walletUpdate($data);
        } else {
            $bool = walletAdd($data);
        }
        
        if ($bool) {
            http_response_code(200);
            echo json_encode(["success" => "achat reussi"]);
            return $bool;
        } else {
            http_response_code(400);
            echo json_encode(["error" => "achat echouer"]);
            return;
        }
    } catch (Exception $e) {
        //error handling server error
        http_response_code(500);
        header("Content-Type: application/json");
        echo json_encode(["DBError"=>"logical erreur wallet"]);
        return;
    }
}

function sell($data){
    $db = DatabaseConnection::getInstance();
    // $token = validateToken();       
    //     if(empty($token)){
    //         http_response_code(401);
    //         echo json_encode(["InvalidCredential" => "Invalid credential"]);
    //         return;
    //     }
    try{
        $wallet = walletFindOne($data,false);
        $detiens = false;
        foreach($wallet as $crypto){
            if($crypto['crypto_name']==$data['crypto_name']){
                $data['transaction_type']="sell";
                $bool = transactionAdd($data);
                 $detiens= true;
                break;
            }
        }
        if($detiens === false){
            http_response_code(500);
            echo json_encode(["InvalideCommande"=> "ne detiens pas le crypto"]);
            return;
        }
        
        if ($data['qte'] <= $crypto['quantite']) {
            $data['qte'] = $crypto['quantite'] - $data['qte'];
        } else {
            http_response_code(500);
            echo json_encode(["InvalideCommande"=> "operation invalide"]);
            return;
        }
        
        if ($data['qte'] == 0) {
            $bool = walletDelete($data); 
        } else {
            $bool = walletUpdate($data);
        }

        if ($bool) {
            http_response_code(200);
            echo json_encode(["success" => "vente reussi"]);
            return $bool;
        } else {
            http_response_code(400);
            echo json_encode(["error" => "vente echouer"]);
            return;
        }
        
    } catch (Exeception $e) {
        http_response_code(500);
        echo json_encode(["DBError"=> "erreur logique sell crypto."]);
        return;
    }
}

?>