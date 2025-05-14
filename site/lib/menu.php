<?php
// Classe Menu
// Gère la création d'un menu HTML dynamique à partir d'une liste d'éléments.
// Attributs :
// - $style : classe CSS appliquée au menu.
// - $composants : tableau contenant les items (liens) du menu.

class Menu{
	private $style;              // Style CSS du menu
	private $composants = [];    // Liste des composants (liens) du menu

	// Constructeur de la classe Menu
	// @param $unStyle string : nom de la classe CSS à appliquer au menu
	public function __construct($unStyle ){
		$this->style = $unStyle;
	}

	// Ajoute un composant (lien) à la liste des composants du menu
	// @param $unComposant : tableau contenant l'identifiant et le texte du lien
	public function ajouterComposant($unComposant){
		$this->composants[] = $unComposant;
	}

	// Crée un item (composant) de menu au format [valeur transmise, texte affiché]
	// @param $unLien : identifiant transmis en GET
	// @param $uneValeur : texte affiché à l'utilisateur
	// @return array contenant l'identifiant et la valeur affichée
	public function creerItemLien($unLien, $uneValeur){
		$composant = array();
		$composant[0] = $unLien;
		$composant[1] = $uneValeur;
		return $composant;
	}

	// Génère le HTML du menu complet avec mise en valeur de l'élément actif
	// @param $composantActif : identifiant de l'item actif
	// @param $nomMenu : nom de la variable transmise dans l'URL
	// @return string HTML du menu
	public function creerMenu($composantActif, $nomMenu){
		$menu = "<ul class = '" . $this->style . "'>";
		foreach($this->composants as $composant){
			if($composant[0] == $composantActif){
				$menu .= "<li class='actif'>";
				$menu .= "<span>" . $composant[1] . "</span>";
			} else {
				$menu .= "<li>";
				$menu .= "<a href='index.php?" . $nomMenu;
				$menu .= "=" . $composant[0] . "' >";
				$menu .= "<span>" . $composant[1] . "</span>";
				$menu .= "</a>";
			}
			$menu .= "</li>";
		}
		$menu .= "</ul>";
		return $menu;
	}
}
