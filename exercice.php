<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
    <script src="frontend/js/api/ApiConnection.js" ></script>
    <script >
        document.addEventListener("DOMContentLoaded", function(){
            var form = document.getElementById("login");
            var resultDiv = document.getElementById('result');
           
            form.addEventListener("submit", function(event) {
                
                event.preventDefault();
                var username = document.getElementById('username').value;
                var password = document.getElementById('password').value;
                //var remember = document.getElementById('remember').checked;

                var data = {
                    "email": username,
                    "password": password
                    //"remember": remember
                };
                
                const connection = new ApiConnection("/session/login");

                connection.postRequest(data,(error, response)=>{
                    if(error){
                        resultDiv.innerHTML = 'Login failed: ' + error;                                
                    } else {
                         response; 
                    }
                });
            });
        });
    </script>


<form id="login">
Username: <input type="text" id="username" name="username">
Password: <input type="password" id="password" name="password"> 

<input type="submit" value="Se connecter" >



</form>

<div id="result">result</div>