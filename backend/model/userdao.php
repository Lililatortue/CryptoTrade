<?php
class user{
    private string $id;
    private string $username;
    private string $pays;
    private string $age;
    private string $role;

    public function __construct($id=null,$usename,$pays,$age,$role){
        setId($id);
        setUsername($username);
        setPays($pays);
        setAge($age);
        setRole($role); 
    }
    //setters
    private function setId($id=null){
        this->id=$id;
    } 
    private function setUsername($username){
        this->username=$username;
    } 
    private function setPays($pays){
        this->pays=$pays;
    } 
    private function setAge($age){
        if($age>18){
            this->age=$age;
        } else {
            throw new Exception("Only people that are above the age of 18 can acces this website");
        }
    } 
    private function setRole($role){
        if($role=='guest' || $role=='Admin'){
            this->role=$role;
        } else {
            throw new Exception("unavailable role");
        }
        
    } 


}


?>