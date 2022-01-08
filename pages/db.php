<?php
    try{
        $pdo=new PDO ("mysql:host=localhost;dbname=reservationsalles", "root", "");
    }
    catch(PDOException $e) {
        echo $e->getmessage();
    }
?>

