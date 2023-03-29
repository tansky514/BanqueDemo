<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
//impotation des classes, db et requette SQL
include "./Domain/Services/dbConnexion.php";
include "./Domain/Entities/Membre.php";
require_once("./Domain/Entities/Alerte.php");
session_start();
if(isset($_GET['pseudonyme']) AND isset($_GET['courriel']) AND isset($_GET['courriel2']) AND isset($_GET['motdepasse']) AND isset($_GET['motdepasse2'])){
    //verification des entrees
    if(!empty($_GET['pseudonyme'])){
        if(!empty($_GET['courriel'])){
            if (!empty($_GET['courriel2'])) {
                if($_GET['courriel']==$_GET['courriel2']){
                    if (!empty($_GET['motdepasse'])) {
                        if (!empty($_GET['motdepasse2'])) {
                            if ($_GET['motdepasse'] == $_GET['motdepasse2']) {
                                $courriel = htmlspecialchars($_GET['courriel']);
                                if (filter_var($courriel, FILTER_VALIDATE_EMAIL)) {
                                    $reqmail = $db->prepare("SELECT * FROM utilisateurs WHERE courriel = ?");
                                    $reqmail->execute(array($courriel));
                                    $mailexist = $reqmail->rowCount();
                                    if ($mailexist == 0) {
                                        //traitement des donnees recus
                                            $pseudonyme = htmlspecialchars($_GET['pseudonyme']);
                                            $mail = htmlspecialchars($_GET['courriel']);
                                            $mail2 = htmlspecialchars($_GET['courriel2']);
                                            $motdepasse = sha1($_GET['motdepasse']);
                                            $motdepasse2 = sha1($_GET['motdepasse2']);
                                            //Appeller la fonction SignUP
                                            $Membre = New  Membre(); 
                                            $Membre->db_connect($db);
                                            $Membre_login = $Membre->signup($pseudonyme, $mail, $motdepasse);
                                            if($Membre_login!=null){
                                                $json = new Alerte("success", $Membre_login);
                                                $json->setRedirection(false,null);
                                            }else{
                                                $json = new Alerte("danger", "Erreur Inconnu");
                                            }
                                    } else {
                                        $json = new Alerte("success", "Adresse mail déjà utilisée !");
                                    }
                                 } else {
                                    $json = new Alerte("success", "Votre adresse mail n'est pas valide !");
                                 }
                            }else{
                                $json = new Alerte("danger", "Vos deux mot de passe ne correspond pas");
                            }
                        }else{
                            $json = new Alerte("danger", "Vueillez confirmer votre mot de passe");
                        }
                    }else{
                        $json = new Alerte("danger", "Vueillez entrer votre mot de passe");
                    }
                }else{
                    $json = new Alerte("danger", "Vueillez deux courriel ne correspond pas");
                }
            }else{
                $json = new Alerte("danger", "Vueillez confirmer votre courriel");
            }
           

        }else{
            $json = new Alerte("danger", "Vueillez entrer votre courriel");
        }
    }else{
        $json = new Alerte("danger", "Vueillez entrer votre pseudonyme");
    }
    echo json_encode($json);
}
?>