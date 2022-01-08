<?php

session_start();
@$login=$_POST["login"];
@$password=$_POST["password"];
@$valider=$_POST["valider"];
$message = "";
if(isset($valider)) {
    include("db.php");
    $res=$pdo->prepare ("SELECT * from utilisateurs where login = ? and password = ? limit 1");
    $res->setFetchMode(PDO::FETCH_ASSOC);
    $res->execute(array($login, md5($password)));
    $tab=$res->fetchAll();
    if (count($tab) == 0){
    $message="<div class = messagered> Mauvais login ou mot de passe (attention aux majuscules).</div>";
    echo ($message);}
    else {
        $_SESSION ["autoriser"] = "oui";
        $_SESSION ["login"]=strtoupper($tab [0]["login"]);
        $_SESSION ["id"]=strtoupper($tab [0]["id"]);
        header("location:pages/profil.php");
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href = "style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>
</head>
<header>
<?php
include('pages/includes/header.html');
?>
</header>
<main>
<body onLoad="document.fo.login.focus()">
<div class = logintitle>Connexion</div>
<div class = loginform>
<form name ="loginform" method = "POST" action = "#" enctype = "multipart/form-data">
    <div class ="label"> Login </div>
    <input type = "text" name="login" value = "" placeholder = Login />           
    <div class = "label"> Mot de passe </div>
    <input type = "password" name ="password" value = ""  /> <br>

   <div class = connectbutton> <input type = "submit" name = "valider" value = "Se connecter" /> </div>
    <p>Pas de compte ?</p><a href = "pages/inscription.php"> S'inscrire </a>
</form>
</div>
</main>
<footer>
<?php
include('pages/includes/footer.html');
?>
</footer>
</body>
</html>