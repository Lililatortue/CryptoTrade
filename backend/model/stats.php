<!--faire les exercices du modulo 8-->

<?php

// function getRendement($data){
//     $db = DatabaseConnection::getInstance();
//     $total_rendement=[];
    
//     try{
//         $stmt = $db->getConnection() -> prepare( "SELECT * FROM v_transaction WHERE email=:email");
//         $stmt -> execute([":email"=>$data['email']]);
//         $crypto=$stmt->fetch(PDO::FETCH_ASSOC);
//         foreach($user as $key=>$value){
//             if($key['transaction_type']=='SELL'){
//                $total_valeur_crypto = $key['quantite']*$key['valeur']; 
//                $total_valeur_user = $key['quantite'] * $key['achat_date'];
//                $total_rendement+=$total_valeur_user - $total_valeur_crypto / $total_valeur_crypto;
//             }                     
//         }

//     } catch() {

//     } 
    

        

//     }
// }

// function Rentabilite($data){
//     $db = DatabaseConnection::getInstance();
    
//     try{
//         $stmt = $db->getConnection() -> prepare( "SELECT * FROM v_transaction 
//                                                   GROUP BY crypto_id
//                                                   ORDER BY transaction_date");
//         $stmt -> execute([":email"=>$data['email']]);
//         $transactions =$stmt->fetch(PDO::FETCH_ASSOC);

//     } catch() {

//     }
// }


function fetchAllTransaction($data){
    $db = DatabaseConnection::getInstance();
    try{
        $stmt = $db->getConnection() -> prepare( "SELECT * FROM v_transaction 
                                                  GROUP BY crypto_id
                                                  ORDER BY transaction_date");
        $transactions = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch() {

    }
}

function fetchUserTransaction(){
    $db = DatabaseConnection::getInstance();
    try{
        $stmt = $db->getConnection() -> prepare( "SELECT * FROM v_transaction 
                                                  GROUP BY crypto_id
                                                  ORDER BY transaction_date
                                                  WHERE email=:email");
        $transactions =$stmt->fetch(PDO::FETCH_ASSOC);

    } catch() {

    }
}

function exer3(){
    $db = DatabaseConnection::getInstance();
    try{
        $stmt = $db->getConnection() -> prepare( "SELECT * FROM v_transaction 
                                                  GROUP BY crypto_id
                                                  ORDER BY transaction_date");
        $transactions =$stmt->fetch(PDO::FETCH_ASSOC);

    } catch() {

    }
    $interaction=[];
    $volume=[];
    foreach($transaction as $transaction){
        $crypto=$transaction['crypto_id'];
        $quantite=$transaction['quantite'];
        $valeur=$transaction['valeur'];
        if(!isset($interaction[$crypto])){
            $interaction[$crypto]=1;
            $volume[$crypto]=0;
        }
        $interaction[$crypto]++;
        //quelqu'un buy quelqu'un doit sell faque je doit creer une table devent dans la db
        if($interaction['transaction_type']=='BUY'){
            $volume[$crypto]+= $quantite * $valeur;
        }
        
    }
    foreach($volume)

    //calculer les variables
    arsort($interaction);
}
?>