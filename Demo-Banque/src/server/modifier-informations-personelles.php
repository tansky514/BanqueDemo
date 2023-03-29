<?php
session_start();
require("./Domain/Entities/Membre.php");
require("./Domain/Services/dbConnexion.php");
require("./Domain/Entities/Alerte.php");
require("./Domain/Entities/RenderInfos.php");
if($_SESSION["id"]){
    $membre = new Membre();
    //Connection a la base de donness
    $membre->db_connect($db);
    //Get User Informations
    $UserInfos = $membre->GetUserInfosByID();
    $renderInfos = new RenderInfos($UserInfos,null);
    if(isset($_GET["nom"]) AND isset($_GET["prenom"]) AND isset($_GET["cellulaire"]) 
    AND isset($_GET["apt"]) AND isset($_GET["no_municipal"])
    AND isset($_GET["rue"]) AND isset($_GET["ville"]) AND isset($_GET["province"])
    AND isset($_GET["pays"])  AND isset($_GET["titre_piece"]) AND isset($_GET["numero_piece"])
    AND isset($_GET["date_emission"]) AND isset($_GET["date_expiration"])){
        //verifier si les entrees sont vides
        if(!empty($_GET["nom"]) AND !empty($_GET["prenom"]) AND  !empty($_GET["cellulaire"]) 
        AND  !empty($_GET["apt"]) AND  !empty($_GET["no_municipal"])
        AND  !empty($_GET["rue"]) AND  !empty($_GET["ville"]) AND  !empty($_GET["province"])
        AND  !empty($_GET["pays"])  AND  !empty($_GET["titre_piece"]) AND  !empty($_GET["numero_piece"])
        AND  !empty($_GET["date_emission"]) AND  !empty($_GET["date_expiration"])){
            //nom et prenom
            $nom = htmlspecialchars($_GET["nom"]);
            $prenom = htmlspecialchars($_GET["prenom"]);
            //informations personnelles
            $cellulaire = htmlspecialchars($_GET["cellulaire"]);
            $apt = intval($_GET["apt"]);
            $no_municipal = htmlspecialchars($_GET["no_municipal"]);
            $rue = intval($_GET["rue"]);
            $ville = htmlspecialchars($_GET["ville"]);
            $province = htmlspecialchars($_GET["province"]);
            $pays = htmlspecialchars($_GET["pays"]);
            //Informations sur la piece d'identite
            $titre_piece = htmlspecialchars($_GET["titre_piece"]);
            $numero_piece = htmlspecialchars($_GET["numero_piece"]);
            $date_emission = $_GET["date_emission"];
            $date_expiration = $_GET["date_expiration"];
            //modifierInformationsPersonnelles
            $membre->setterNomPrenom($nom,$prenom);
            $membre->insererKYC($titre_piece,$numero_piece,$date_emission,$date_expiration);
            $message = $membre->modifierInformationsPersonnelles($cellulaire,$apt,$no_municipal,$rue,$ville,$province,$pays);
            $alert = new Alerte("success","Informations enregistrer avec success");
        }else{
            $alert = new Alerte("danger","Tous les informations doivent etre completes");
        }
    }
    $renderInfos->setStatus($alert);
    echo json_encode($renderInfos);

}



?>