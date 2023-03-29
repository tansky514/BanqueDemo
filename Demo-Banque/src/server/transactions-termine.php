<?php
session_start();
//importation des clasess
require_once("./Domain/Entities/Alerte.php");
require_once("./Domain/Entities/Membre.php");
require_once("./Domain/Entities/Transaction.php");
require_once("./Domain/Entities/RenderInfos.php");
require_once("./Domain/Entities/Status.php");
require("./Domain/Services/dbConnexion.php");

$membre = new Membre();
//Connection a la base donnees
$membre->db_connect($db);
//Requellir des informations sur l'utilisateur connecté ::
if($_SESSION["id"]!=null){
        if($membre->PremiereConnection()==true){
            $alert = new Alerte(null,null);
            $alert->setRedirection(true,"modifier-informations-personelles");
            $renderInfos = new RenderInfos(null,null);
            $renderInfos->setStatus($alert);
        }else{
                $UserID = intval($_SESSION["id"]);
                $UserInfos = $membre->GetUserInfosByID();
                //Requellir tous les transactions
                $UserTransactions = $membre->getUserTransactions(Status::Terminé);
                //Generer les informations :: 
                $renderInfos = new RenderInfos($UserInfos,$UserTransactions);
                
            }
}else{
    $alert = new Alerte(null,null);
    $alert->setRedirection(true,"Login");
    $renderInfos->setStatus($alert);
}
echo json_encode($renderInfos);
?>