<?php
session_start();
//importation des clasess
require_once("./Domain/Entities/Alerte.php");
require_once("./Domain/Entities/Membre.php");
require_once("./Domain/Entities/Transaction.php");
require_once("./Domain/Entities/RenderInfos.php");
require_once("./Domain/Entities/Status.php");
require("./Domain/Services/dbConnexion.php");

$alert = new Alerte(null,null);
$membre = new Membre();
//Connection a la base donnees
$membre->db_connect($db);
//Requellir des informations sur l'utilisateur connecté :\
if($_SESSION["id"]!=null){
    $UserID = intval($_SESSION["id"]);
    if($membre->PremiereConnection()==true){
        $alert = new Alerte(null,null);
        $alert->setRedirection(true,"modifier-informations-personelles");
        $renderInfos = new RenderInfos(null,null);
        $renderInfos->setStatus($alert);
    }else{
        $UserInfos = $membre->GetUserInfosByID();
        //Debut de la transactions
        if(isset($_GET["courriel1"]) AND isset($_GET["courriel2"])
         AND isset($_GET["montant"]) AND isset($_GET["notes"]) ){
           if(!empty($_GET["courriel1"])){
            if(!empty($_GET["courriel2"])){
                if($_GET["courriel1"]==$_GET["courriel2"]){
                    if($_GET["montant"]!=null){
                        $email = htmlspecialchars($_GET["courriel1"]);
                        $email2 = htmlspecialchars($_GET["courriel2"]);
                        $notes = htmlspecialchars($_GET["notes"]);
                        $montant = $_GET["montant"];
                        $message = $membre->newTransfert($email,$montant,$notes);
                        if($message=="Transfert fait avec success"){
                            $alert = new Alerte("success",$message);
                        }else{
                            $alert = new Alerte("danger",$message);
                        }
                    }else{
                    $alert = new Alerte("danger","Veuillez entrer un montant valide");
                    }
                }else{
                    $alert = new Alerte("danger","Les deux emails ne correspond pas ");
                }
            }else{
                $alert = new Alerte("danger","Veuillez confirmer l'email du beneficiaire");
            }
           }else{
            $alert = new Alerte("danger","Vueillez entrer l'email du beneficiaire");
           }
        }else{
            $alert = new Alerte("danger","Erreur");
        }   
    }   
}else{
    $alert = new Alerte(null,null);
    $alert->setRedirection(true,"Login");
}
echo json_encode($alert);
?>