<?php
session_start();
//importation des clasess
//Avec require_once, cela n'arrivera pas car 
//...le fichier sera inclus une seule fois, même si l'instruction est exécutée plusieurs fois.
require_once("./Domain/Entities/Alerte.php");
require_once("./Domain/Entities/Membre.php");
require_once("./Domain/Entities/Transaction.php");
require_once("./Domain/Entities/RenderInfos.php");
require_once("./Domain/Entities/Status.php");
//require inclut le fichier spécifié à chaque fois que 
//...l'instruction est exécutée, ce qui signifie que si le fichier a déjà été inclus, il sera inclus à nouveau.
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
        $UserTransactions = $membre->getUserTransactions(Status::Tous);
        //Generer les informations :: 
        $renderInfos = new RenderInfos($UserInfos,$UserTransactions);
    }
    
}else{
    $alert = new Alerte(null,null);
    $alert->setRedirection(true,"Login");
    $renderInfos->setStatus($alert);
}

?>