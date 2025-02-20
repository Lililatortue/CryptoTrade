<?php
//ici on peux enforcer les invariances

class DTO{
    private string $username;
    private string $pays;
    private string $age;
    private string $role;
    private string $password;
    //builder pour creation d'un utilisateur
    public createUser($data){
        setUsername($data['username']);
        setPays($data['pays']);
        setAge($data['age']);
        setRole($data['role']);
        setPassword($data['password']);
    }
    public getUser($data);{
        setEmail($data['email']);
    }
    public returnUser($data){
        setUsername($data['username']);
        setPays($data['pays']);
        setAge($data['age']);
        setRole($data['role']);
    }
    //setters
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

    setPassword($psw){
        $this->password=pbkdf2_hmac($psw);
    }

}


?>