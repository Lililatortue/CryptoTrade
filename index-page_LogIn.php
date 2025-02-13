<?php
  //Données temporaire pour tester
  $adm_username = "admin";
  $adm_password = "1234";

  //variable pour stoquer msg d'erreur
  //(qui apparait en dessous en rouge, quand on clique le boutton)
  $error_MSG = "";
  $error_class = "hidden"; //elle va etre hidden quand on load pour la 1re fois

  //Debut .php 
  if ($_SERVER["REQUEST_METHOD"] == "POST") 
  {     
    //Pogne les User Inputs
    $username = $_POST['username'];
    $password = $_POST['password'];
    //img reste a opacité '1'(100%) pour eviter un loop d'animation (voir le .CSS)
    $img_opacity = "1";

    //Validations des données INPUT avec la BD
    if ($username == $adm_username && $password == $adm_password) 
    { //change de page (test)
      header("Location: test_page.php"); 
      exit(); 
    } 
    else 
    {  
      $error_class = "visible"; 
      $error_MSG = "Nom d'utilisateur ou mot de passe incorrect.";
    } //Montrer msg d'erreur 
  }
?>

<!DOCTYPE html>
<html>
<head> 
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" type="text/css" href="./frontend/css/theIndex.css"> <!--css-->
 <title> Bienvenue </title>
 <!--insert JS script here-->
 <script>

</script>
</head>
<body bgcolor="grey">
  <div class="pageContainer"> 
  <h1> Crypto Trade </h1>
  <div style="display: flex;"> <!--guarder dans meme ligne-->
  <img id="page_LoGo" style="opacity: <?php echo $img_opacity; ?>" src="./frontend/Img/ThumbsUp-guy.png" alt="siteLogo"> <br/>
  <form method="post">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username"><br><br>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password"><br><br>

        <button type="submit">Se connecter</button>  
        <br/><br/> 
        <!--visibility = msg d'erreur. Commence 'hidden'-->
        <span style="visibility: <?php echo $error_class; ?>; color: red; font-weight: bold;" >
          <!--Commence vide "". Si input == wrong, afficher l'erreur (dans le ?php)-->
          <?php echo $error_MSG; ?>  
        </span>
    </form>
  </div>
  </div>
</body>
</html>

