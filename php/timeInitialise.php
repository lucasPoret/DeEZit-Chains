<?php
session_start();
if (!(isset($_SESSION["username"]))) {
    header('Location: login.php');
    exit();
}
else{
    require './connexion_db.php';
    if (!$connexion) {
        header('Location: login.php?error=db');
        exit();
    }
    $pseudo = $_SESSION["username"];
    $requete = "UPDATE user SET current_time_trial = '0' WHERE username = '$pseudo'";
    $resultat = mysqli_query($connexion, $requete); //Executer la requete
    if (isset($connexion) && $connexion !== false) {
        mysqli_close($connexion);
    }
    header('Location: time.php');
    exit();
}
