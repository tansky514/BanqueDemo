<?php
class Membre{
    private $_db;
    private $_ID;
    private $pseudo;
    private $courriel;
    private $motdepasse;


    function db_connect($db_string){
        $this->_db = $db_string;
    }
    function Login($courriel,$pwd)
    {
      $requser = $this->_db->prepare("SELECT * FROM utilisateurs WHERE courriel = ? AND motdepasse = ?");
      $requser->execute(array($courriel, $pwd));
      $userexist = $requser->rowCount();
      if($userexist == 1) {
         $userinfo = $requser->fetch();
         //On garde en session l'id et courriel de l'utilisateur
         $_SESSION['id'] = $this->_ID =  $userinfo['id'];
         $this->courriel = $userinfo['courriel'];
         //l'access sera valider apres l'entrer du bon otp
         //$_SESSION['otp_access'] = 0;
         //On genere un code aleatoire pour authentification a 2 facteur
         $Nombre_Aleatoire= intval($this->GenererNombreAleatoire());
         $this->EnvoieDesNombresViaEmail($Nombre_Aleatoire);
            return $userinfo['id'] ;
      } else {
            return null;
      }
    }
    function GenererNombreAleatoire() {
        //$randomNumber = rand(10000, 99999);
        $randomNumber = 1000; // a corriger apres le deploiement
        return $randomNumber;
    }
    function EnvoieDesNombresViaEmail($nombreAleatoire){
        $email_utilisateur = $this->courriel;
        try{
            error_reporting(0);
            ini_set('display_errors', 0);
           $otp_code_generer = $nombreAleatoire;
           $from = "www.demo-banque.com";
           $to = $email_utilisateur;
           $subject = "Mot de passe à usage unique (OTP)";
           $message = "Votre Code est " . $otp_code_generer;
           $headers = "De :" . $from;
           mail($to, $subject, $message, $headers);
           $_SESSION["otp_code"] = $otp_code_generer;
           return null;
        }catch(Exception $e){
            return null;
        }
    }
    //fonction pour l'inscription de l'utilisateur
    function Signup($pseudo,$courriel,$motdepasse){
       try{
        $insertmbr = $this->_db->prepare("INSERT INTO utilisateurs(pseudonyme, courriel, motdepasse) VALUES(?, ?, ?)");
        $insertmbr->execute(array($pseudo, $courriel, $motdepasse));
        return "Votre compte a bien été créé !";
       }catch(Exception $e){
            return null;
       }
    }

    //A faire
    function PremiereConnection(){
        $this->_ID = $UserID = intval($_SESSION["id"]);
        //verifier si les renseignements personnelles sont enregistrer
        $reqRenseignementPersonelles = $this->_db->prepare("SELECT utilisateur FROM  renseignement_personnel WHERE id = ?");
        $reqRenseignementPersonelles->execute(array($UserID));
        //si les renseignements personnelles n'existent pas 
        if($reqRenseignementPersonelles->rowCount()==0){
            $reqDocumentID = $this->_db->prepare("SELECT * FROM document_identification WHERE utilisateur = ?");
            $reqDocumentID->execute(array($UserID));
            if($reqDocumentID->rowCount()==0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


    function GetUserInfosByID(){
        $UserInfos = $this->_ID = intval($_SESSION["id"]);
        //Req with ID;
        $UserInfosSQLQUERY = $this->_db->prepare("SELECT renseignement_personnel.ville,renseignement_personnel.cellulaire,
        renseignement_personnel.apt,renseignement_personnel.no_municipal,renseignement_personnel.rue,renseignement_personnel.province,
        renseignement_personnel.pays,utilisateurs.nom,utilisateurs.prenom,utilisateurs.date_naissance,utilisateurs.courriel 
        FROM renseignement_personnel 
        INNER JOIN utilisateurs
        ON renseignement_personnel.utilisateur = utilisateurs.id
        WHERE utilisateur = ? ");
          $UserInfosSQLQUERY->execute(array($UserInfos));
         return $UserInfosSQLQUERY->fetch();
    }
    

    function getUserTransactions($status) {
        $this->_ID = intval($_SESSION["id"]);

        if($status==null){
            $userTransactionsQuery = $this->_db->prepare("SELECT livrets.envoyeur,livrets.receveur,
            livrets.montant,livrets.montant_reel,livrets.date_transactions,livrets.notes,livrets.status,utilisateurs.nom AS nom_receveur,
            utilisateurs.prenom AS prenom_receveur
            FROM livrets  INNER JOIN utilisateurs ON livrets.receveur = utilisateurs.id
            WHERE envoyeur = ? OR receveur = ?");
            $userTransactionsQuery->execute(array($this->_ID, $this->_ID));
        }else{
            $userTransactionsQuery = $this->_db->prepare("SELECT livrets.envoyeur,livrets.receveur,
            livrets.montant,livrets.montant_reel,livrets.date_transactions,livrets.notes,livrets.status,utilisateurs.nom AS nom_receveur,
            utilisateurs.prenom AS prenom_receveur
            FROM livrets  INNER JOIN utilisateurs ON livrets.receveur = utilisateurs.id
            WHERE (envoyeur = ? OR receveur = ? ) AND status=?");
             $userTransactionsQuery->execute(array($this->_ID, $this->_ID,$status));
        }
        $transactions = array();
        while($row = $userTransactionsQuery->fetch()) {
            $montant = (-($row["montant_reel"]));
            $transaction = new Transaction($row["nom_receveur"],$row["prenom_receveur"], $row["envoyeur"], $montant, $row["status"], $row["notes"]);
            array_push($transactions, $transaction);
        }
        return $transactions;

    }
    function newTransfert($email,$montant,$notes){
        $UserID = intval($_SESSION["id"]);
        $ID_receveur = intval($this->getUserIdByEmail($email));
        $ID_envoyeur =  $UserID;
        
        if($ID_receveur!=null){
            if($montant>=10){
                //Calcul des frais
                $montant_reel = - $montant;
                $frais = $montant*5/100;
                $montant_net_envoyer = $montant-$frais;
                //Transfere des fonds 
                $TransfertSQL = $this->_db->prepare("INSERT INTO livrets(envoyeur,receveur,montant,montant_reel,frais,notes,date_transactions) VALUES (?,?,?,?,?,?,?)");
                $TransfertSQL->execute(array($ID_envoyeur,$ID_receveur,$montant_net_envoyer,$montant_reel,$frais,$notes,date("Y-m-d H:i:s")));

                return "Transfert fait avec success";
            }else{
                return "Le montant doit etre superieur ou egal a 10";
            }
        }else{
            return "L'utilisateur n'existe pas";
        }

    }

    function getUserIdByEmail($email){
        $SQL_User_Id_By_Email = $this->_db->prepare("SELECT id FROM utilisateurs WHERE courriel = ?");
        $SQL_User_Id_By_Email->execute(array($email));
        $UserExist = $SQL_User_Id_By_Email->rowCount();

        if($UserExist==1){
            $UserID = $SQL_User_Id_By_Email->fetch()["id"];
        }else{
            $UserID = 0;
        }
         return $UserID;
    }
    function setterNomPrenom($nom,$prenom){
        // Create connection
        $conn = $this->_db;
        $this->_ID = $UserId = intval($_SESSION["id"]);
        $stmt = $conn->prepare("UPDATE utilisateurs SET nom=?,prenom=? WHERE id=?");
        $stmt->execute(array($nom,$prenom,$UserId));
    }
    function insererKYC($titre,$numero,$date_emission,$date_expiration){
        $conn = $this->_db;
        $this->_ID = $UserId = intval($_SESSION["id"]);
        $stmt = $conn->prepare("INSERT INTO document_identification(utilisateur,titre,numero,date_emission,date_expiration) VALUES(?,?,?,?,?)");
        $stmt->execute(array($UserId,$titre,$numero,$date_emission,$date_expiration));
    }
    function modifierInformationsPersonnelles($cellulaire, $apt, $no_municipal, $rue, $ville, $province, $pays) {
        // Create connection
        $conn = $this->_db;
        $this->_ID = $UserId = intval($_SESSION["id"]);
        // Verification des informations personnelles
        $verif_sql = $conn->query("SELECT * FROM renseignement_personnel");
        if($verif_sql->rowCount()==0){
            //Inserer les informations personnelles
            try{
                // Prepare the SQL query
                $stmt = $conn->prepare("INSERT INTO renseignement_personnel(utilisateur,cellulaire,apt,no_municipal,rue,ville,province,pays) 
                VALUES(?,?,?,?,?,?,?,?)");
                // Bind parameters to the prepared statement
                $stmt->execute(array($UserId,$cellulaire, $apt, $no_municipal, $rue, $ville, $province, $pays));
                return "Informations enregistrees avec success";
            }catch(Exception $e){
                return "Erreur".$e;
            }
        }else{
            $stmt = $conn->prepare("UPDATE renseignement_personnel SET cellulaire = ?,apt = ?,no_municipal = ?,
            rue = ?,ville = ?,province = ?,pays = ? WHERE utilisateur = ? ");
            $stmt->execute(array($cellulaire, $apt, $no_municipal, $rue, $ville, $province, $pays,$UserId));
            return "Mofifier Avec Success";
        }

        
}

}
?>