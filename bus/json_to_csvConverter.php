<?php

function counts(&$index,$time){
    sleep($time);
    $index++;
}

    $i=0;
    $j=0;
    while($i!=10){
        $j++;
    
    
    
        exec(counts($i,1));
    } 
    printf("J: %d\n",$j);
    printf("I: %d",$i);

?>