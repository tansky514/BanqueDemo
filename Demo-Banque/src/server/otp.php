<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//importation des clasess
require_once("./Domain/Entities/Alerte.php");
require_once("./Domain/Entities/Membre.php");
$_COOKIE["test"] =10;
if(isset($_SESSION["id"]) AND isset($_SESSION["otp_access"])){
    if($_SESSION["otp_access"]==0){
        if(isset($_GET["otp_code_client"])){
            $otp_code_client = $_GET["otp_code_client"];
            $otp_code_generer = $_SESSION["otp_code"];
                if(!empty($otp_code_client) AND $otp_code_client>0){
                    if($otp_code_client==$otp_code_generer){
                        $_SESSION["otp_access"]=1;
                        $alert = new Alerte(null,null);
                        $alert->setRedirection(True,"modifier-informations-personelles");
                    }else{
                        $alert = new Alerte("danger","Code otp incorrect");
                    }
                }else{
                $alert = new Alerte("danger","Code OTP Invalide");
                }
            }else{
                $alert = new Alerte("danger","Vueillez contacter le service tecnique");
            }
    }else{
        $alert = new Alerte(null,null);
        $alert->setRedirection(True,"modifier-informations-personelles");
    }
}else{
    $alert = new Alerte(null,null);
    $alert->setRedirection(True,"error");
}
echo json_encode($alert);

?>