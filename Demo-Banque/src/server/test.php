<?php
session_start();
require("./Domain/Entities/Membre.php");
require("./Domain/Services/dbConnexion.php");
require("./Domain/Entities/Alerte.php");

$member = new Membre();

$member->db_connect($db);
$UserId = $_SESSION["id"];

echo $member->PremiereConnection();



?>