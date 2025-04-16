<?php 
include_once  __DIR__ . "/../dbdata/config.php";
include_once  __DIR__ . "/../dbdata/dbConnection.php";
include_once  __DIR__ . "/../model/crypto.php";
include_once  __DIR__ . "/../model/cryptoHistorique.php";
function logMessage($message) {
        $logfile = "C:\\Users\\willd\\OneDrive\\Desktop\\PHPProject\\CryptoTrade\\backend\\service\\check.log";
        file_put_contents($logfile, date('Y-m-d H:i:s') . ' - ' . $message . "\n", FILE_APPEND);
    }
//script qui roule toute les 20 mins
//j'utilise mon os (MS service) pour le faire rouler asynchrone au program
logMessage("20min have passed");

    $allCrypto = fetchAllCrypto();
    foreach($allCrypto as &$crypto){
        //creation de la tendance (SMA)
        $tendance  = Historiquefetch($crypto['name'],10);

        if(!empty($tendance)){
            $total=0;
            foreach($tendance as $hist){
                $total+=$hist['closing_price'];
            }
            $sma=$total/count($tendance);
        } else {//valeur par default
            $sma = 1; 
        }
        //appliquer la variation
        $var=($crypto['price_usd'] < $sma) ? 1.002 : 0.998;

        if($crypto['volatilite']=='petit'){
            $fluctuation=$var*(1+(mt_rand(-5,5)/1000));}
        else if($crypto['volatilite']=='moyen'){
            $fluctuation=$var*(1+(mt_rand(-20,20)/1000));}
        else {
            $fluctuation=$var*(1+(mt_rand(-500,500)/1000));}

        $crypto['price_usd']=$crypto['price_usd'] * $fluctuation;
        echo $crypto['name']."\t". $crypto['price_usd']."\n";

    }
    unset($crypto);
    $db = DatabaseConnection::getInstance()->getConnection();
    $db->beginTransaction();

    $succes=true;
    var_dump($allCrypto);
    foreach($allCrypto as $crypto){
        $bool1=updatePriceCrypto($db,$crypto);
        $bool2=HistoriqueAdd($db,$crypto);

        if (!$bool1 || !$bool2) {
            $succes = false;
            break;
        }
    }
   
    
    logMessage("Début de l'exécution du script.");
    if ($succes) {
        $db->commit();
        logMessage( "Prix mis à jour et historique enregistré.");
    } else {
        $db->rollBack();
        logMessage("Échec: annulation des opérations.");
    }

   
?>