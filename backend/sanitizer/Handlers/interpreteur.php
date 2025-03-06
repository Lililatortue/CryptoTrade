<?php
class Tokenizer{
    public function tokenize($route){
        $logic=[];
        $list_of_cmd=explode(" ",$route);
        foreach($list_of_cmd as $str){
            $commandes=[];
            if(ctype_digit($str)){
                    $commande["value"]=(int)$str;
                     $commande["type"]="NUMBER";
            }
            else if($str=="=="||$str=="=>"||$str=="=<"){
                    $commande["value"]=$str;
                    $commande["type"]="COMPARAISON";
            }
            else if($str=="->"){
                    $commande["value"]=$str; 
                    $commande["type"]="ALSO";
            }
            if(empty($commande)){
                return "unknown commande";
            } else {
                $logic[]=$commande;
            }
        }
        return $logic;
    }
}

class Parser{
    //je me suis pas casse la tete... il est minuit si je vois le besoin je changerai
    // a un expression tree si possible
    public function PostfixNotation($logic){
        $postfix=[];
        foreach($logic as $key=>$action){
            if($action["type"]=="ALSO"){
                array_push($postfix,$action);
                unset($logic[$key]);
            }
        }
        foreach($logic as $key=>$action){
            if($action["type"]=="COMPARAISON"){
                array_push($postfix,$action);
                unset($logic[$key]);
            }
        }
        foreach($logic as $action){
            array_push($postfix,$action);
        }
        return $postfix;
    }
    public function CreateFuncts($logic,$routes){
        $queue= new \DS\Queue();
        while($action=array_pop($logic)){
            if($action['type']=="NUMBER"){
                $queue->push($action);
            }
            else if($action['type']=="COMPARAISON"){
                switch($action['value']){
                case "==": $fonction = fn($val)=> $val==$queue->pop();
                           $queue->push($fonction); break;
                    
                case "=>": $fonction = fn($val)=> $val=>$queue->pop();
                           $queue->push($fonction); break;
                    
                case"=<":  $fonction = fn($val)=> $val=<$queue->pop();break; 
                           $queue->push($fonction); break;
                }
             } 
            else if($action['type']=="ALSO"){
                $fonction = fn($val1,$val2)=> $val2
            } 
            }
        }

    }
}

$token=new Tokenizer();
$parser=new Parser();
//test
$array=$token->tokenize("== 14 -> =< 5");
foreach($parser->PostfixNotation($array) as $key=>$action){
echo $action['type']."\t".$action['value']."<br>";
};
//deviens -> == 14 =< 5 
?>