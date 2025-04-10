
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
    <title>Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="../../frontend/js/api/ApiConnection.js" ></script>
    <script >
        document.addEventListener("DOMContentLoaded", function(){
            var form = document.getElementById("login");
            //var resultResultDiv = document.getElementById('request _result');
           
            form.addEventListener("submit", function(event) {
                
                event.preventDefault();
                var email = document.getElementById('email').value;
                var password = document.getElementById('password').value;
                //var remember = document.getElementById('remember').checked;

                var data = {
                    "email": email,
                    "password": password
                    //"remember": remember
                };
                //xml ovbjects
                const connection = new ApiConnection("/session/login");

                connection.postRequest(data,(error, response)=>{
                    if(error){
                        resultResultDiv.innerHTML = 'Login failed: ' + error;                                
                    } else {
                        response; 
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
           
        <form id="login">
            <label for="Title" class="form-title"><h3><b>Login</b></h3></label>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">remember me</label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="register" class="btn btn-primary">register</button>
        </form>
 </div>


<body