<?php
class Utilisateur {
    // Données de l’utilisateur
    private $nom;
    private $prenom;
    private $login;
    private $mdp;
    private $typeUser;
    private $idFonct;
    private $idLigue;
    private $idClub;
    private $idUser;

    // Constructeur : initialise tous les attributs à une valeur vide
    public function __construct() {
        $this->nom       = "";
        $this->prenom    = "";
        $this->login     = "";
        $this->mdp       = "";
        $this->typeUser  = "";
        $this->idFonct   = "";
        $this->idLigue   = "";
        $this->idClub    = "";
        $this->idUser    = "";
    }

    // Initialise les identifiants de connexion de l’utilisateur
    public function userConnex($login, $mdp) {
        $this->login = $login;  // stocke le login
        $this->mdp   = $mdp;    // stocke le mot de passe (clair)
    }
}
?>
