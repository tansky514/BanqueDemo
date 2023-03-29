<?php
class Transaction{
    public $beneficiaire;
    public $envoyeur;
    public $montant;
    public $status;
    public $notes;

    function __construct($nom,$prenom,$envoyeur,$montant,$status,$notes){
        $this->beneficiaire = $nom." ".$prenom;
        $this->envoyeur = $envoyeur;
        $this->montant = $montant;
        $this->status = $status;
        $this->notes = $notes;
    }
}


?>