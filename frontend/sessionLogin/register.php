<?php
/**
 * TODO : make session cookie
 * 
 */


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>register Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="../../frontend/js/api/ApiConnection.js" ></script>
    <script >
        document.addEventListener("DOMContentLoaded", function(){
            var form = document.getElementById("Register");
            var resultResultDiv = document.getElementById('request _result');
           
            form.addEventListener("submit", function(event) {
                
                event.preventDefault();
                var username = document.getElementById('username').value;
                var email = document.getElementById('email').value;
                var pays = document.getElementById('pays').value;
                var age = document.getElementById('age').value;
                var password = document.getElementById('password').value;
                //var remember = document.getElementById('remember').checked;

                var data = {
                    "username":username,
                    "email": email,
                    "pays":pays,
                    "age":age,
                    "password": password
                };
                //xml ovbjects
                const connection = new ApiConnection("/user/add");

                connection.postRequest(data,(error, response)=>{
                    if(error){
                        resultResultDiv.innerHTML = 'Register failed: ' + error;                                
                    } else {
                        resultResultDiv.innerHTML =response; 
                    }
                });
            });
        });
    </script>


<style>
    body, html {
    height: 100%;
    margin: 0;
    display: grid;
    background-color:rgba(11, 12, 14, 0.19); /* Example background color */
}
        .container-sm {
            width: 90%; 
            margin: auto;
            border: 2px solid rgb(27, 86, 149); 
            padding: 20px;
            border-radius: 5px; 
            background-color:rgb(154, 181, 207);
            width: 30%
        }
        .form-control , .form-check-input {
            border: 2px solid rgb(27, 86, 149);  
            background-color:rgb(185, 189, 194);
        }

</style>
</head>
<body>
 <div class="container-sm">
           
        <form id="Register">
            <label for="Title" class="form-title"><h3><b>Register</b></h3></label>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="username" class="form-control" id="username" >
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                    <label for="pays" class="form-label">pays</label>
                    <input type="pays" class="form-control" id="pays">
            </div>
            <div class="mb-3">
                    <label for="age" class="form-label">age</label>
                    <input type="age" class="form-control" id="age">
            </div>
            <div class="mb-3">
                    <label for="password" class="form-label">password</label>
                    <input type="password" class="form-control" id="password">
            </div>
        
            <button type="submit" class="btn btn-primary">Submit</button>
            <div id="request _result">input</div>
        </form>
 </div>


<body