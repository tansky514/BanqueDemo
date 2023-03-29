<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
//impotation des classes, db et requette SQL
include "./Domain/Services/dbConnexion.php";
include "./Domain/Entities/Membre.php";
require_once("./Domain/Entities/Alerte.php");
session_start();
if(isset($_GET['courriel']) AND isset($_GET['motdepasse'])){
    //verification des entrees
    if(!empty($_GET['courriel'])){
        if(!empty($_GET['motdepasse'])){
            //traitement des donnees recus
            $courriel = htmlspecialchars($_GET['courriel']);
            $password = sha1($_GET['motdepasse']);
            //Appeller la fonction login
            $Membre =New  Membre();
            $Membre->db_connect($db);
            $Membre_login = $Membre->login($courriel,$password);
            if($Membre_login!=null){
                $_SESSION['otp_access'] = 0;
                $json = new Alerte("success", "Utilisateur Connecter");
                $json->setRedirection(True,"otp");
            }else{
                $json = new Alerte("danger", "Mauvais courriel ou mot de passe");
            }
        }else{
            $json = new Alerte("danger", "Vueillez entrer votre mot de passe");
        }
    }else{
        $json = new Alerte("danger", "Vueillez entrer votre email");
    }
    echo json_encode($json);
}
?>