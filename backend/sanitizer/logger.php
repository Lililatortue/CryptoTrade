<?php
enum logType {
    case ERROR;
    case ACTION;
    case DEBUG;
    case INFO;
}

class logger{
    private static $instance=null;
    private $year;
    private $month;
    private LogType $type;

    private $call_tag=null;
    private $action_time=null;
    private $start=null;

    private function __construct($call_tag,$directory,LogType $type){
            date_default_timezone_set('America/New_York');
            $this->year  = date('Y');
            $this->month = date('F');
            $this->setdirectory($directory);
            $this->type = $type;
            $this->call_tag=$call_tag;
    }

    public static function getLoggerInstance($name, $directory,LogType $type){
        if(self::$instance === null){
            self::$instance = new self($name, $directory, $type);
        }
           return self::$instance;
        
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
    private function startTimer(){
        $this->start = microtime(true);
    }
    public function EndTimer(){
        $end = microtime(true);
        $this->action_time=( $end - $this->start )*1000;
        $this->end=null;
    }
    public function log(callable $func, $ip, $web_browser, $routes){
        $this->setTrue();
        $this->startTimer();

        if(empty($resultat = $func()) ) $this->setFalse();

        $this->EndTimer();
        $this->logging($ip,$web_browser,$routes);
    }

    public function logging($ip,$web_browser,$routes){   
        $log_path=$this->directory.'/'.$this->month.'.log';
        $logs= "DAY: [ ".date('d H:i:s')." ]\n\tIP: ".$ip;
        $logs.= "\n\tTYPE: ".$this->call_tag;
        if(isset($this->name))
            $logs.= "\n\tTYPE: ".$this->call_tag;
        $logs.="\n\tWEB BROWSER: ".$web_browser;
        $logs.="\n\tROUTE: ".$routes;
        $logs.="\n\tRESULT: ".($this->action_state==true ? "SUCCES" : "FAIL");
        if($this->type=== LogType::ACTION)
            $logs.= "\n\tTIME: ".
            (isset($this->action_time) ? $this->action_time." ms" : "NOT RECORDED");
        $logs.="\n------------------------------------------------------------------------------\n";
        file_put_contents($log_path, $logs,FILE_APPEND);    
    }
}
?>