<?php
enum logType {
    case ERROR;
    case ACTION;
    case DEBUG;
}
class logger{
    
    private $year;
    private $month;
    private $day;
    private $name;
    private LogType $type;

    private $action_state=null;
    private $action_time=null;
    private $start=null;
    public function __construct($name,$directory,LogType $type){
        date_default_timezone_set('America/New_York');
        $this->year  = date('Y');
        $this->month = date('F');
        $this->day   = date('d H:i:s');
        $this->type = $type;
        $this->name=$name;
        $this->setdirectory($directory);
    }
    //setters
    private function setdirectory($directory){
        $directory.=$this->year;
        if(!is_dir($directory)){
            mkdir($directory,0644,true);
        }
        $this->directory=$directory;
    }
    //maniere de verifier si une action est reussi ou non
    public function setTrue(){
        $this->action_state=true;
    }
    public function setFalse(){
        $this->action_state=false;
    }
    //functions
    //calculer le temps que sa prends faire une reponse 
    public function startTimer(){
        $this->start = microtime(true);
    }
    public function EndTimer(){
        $end = microtime(true);
        $this->action_time=( $end - $this->start )*1000;
        $this->end=null;
    }


    public function logging($ip,$web_browser,$routes,$result,$data){   
        $log_path=$this->directory.'/'.$this->month.'.txt';

        
        $logs= "DAY: [ ".$this->day." ]\n\tIP: ".$ip;

        if(isset($this->name))
            $logs.= "\n\tTYPE: ".$this->name;
        
        $logs.="\n\tWEB BROWSER: ".$web_browser;
        $logs.="\n\tROUTE: ".$routes;
        $logs.="\n\tDATA SENT: ".json_encode($data);

        $logs.="\n\tRESULT: ".($this->action_state==true ? "SUCCES" : "FAIL");

        $logs.="\n\t".($this->action_state==true ? "DATA SENT: ":"ERROR MESSAGE: ")
        .json_encode($result);

        if($this->type=== LogType::ACTION)
            $logs.= "\n\tTIME TO COMPLETE ACTION: ".
            (isset($this->action_time) ? $this->action_time." ms" : "NOT RECORDED");
        $logs.="\n------------------------------------------------------------------------------\n";
        file_put_contents($log_path, $logs,FILE_APPEND);    
    }
}
?>