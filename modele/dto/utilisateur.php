<?php
class Utilisateur{
    private $nom;
    private $prenom;
    private $login;
    private $mdp;
    private $typeUser;
    private $idFonct;
    private $idLigue;
    private $idClub;
    private $idUser

    public function __construct(){
        $this->nom = "";
        $this->prenom = "";
        $this->login = "";
        $this->mdp = "";
        $this->typeUser = "";
        $this->idFonct = "";
        $this->idLigue = "";
        $this->idClub = "";
    }

    public function userConnex($login, $mdp){
        new Utilisateur();
        $this->login = $login;
        $this->mdp = $mdp;
    }
    
}