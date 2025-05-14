<?php
// 1) Récupère toutes les ligues
$ligues = liguesDAO::getAllLigues();

// 2) Si une ligue est sélectionnée en GET
if (isset($_GET['ligue'])) {
    // 2a) Récupère les détails de la ligue
    $ligueInfo = liguesDAO::getById($_GET['ligue']);

    // 2b) Prépare un formulaire en lecture seule
    $SecondaryBody = new Formulaire('post', 'index.php', 'ligues', 'ligues');

    // 2c) Champ nom de la ligue (readonly)
    $SecondaryBody->ajouterComposantLigne(
        $SecondaryBody->creerInputTexte(
            'nomLigue',
            'nomLigue',
            $ligueInfo['nomLigue'],0,'','',true)
    );
    $SecondaryBody->ajouterComposantTab();

    // 2d) Champ site de la ligue (readonly)
    $SecondaryBody->ajouterComposantLigne(
        $SecondaryBody->creerInputTexte(
            'siteLigue',
            'siteLigue',
            $ligueInfo['site'],0,'','',true)
    );
    $SecondaryBody->ajouterComposantTab();

    // 2e) Champ description de la ligue (readonly)
    $SecondaryBody->ajouterComposantLigne(
        $SecondaryBody->creerInputTexte(
            'descriptionLigue',
            'descriptionLigue',
            $ligueInfo['description'],0,'','', true)
    );
    $SecondaryBody->ajouterComposantTab();

    // 2f) Génère le formulaire HTML
    $SecondaryBody->creerFormulaire();
}

// 3) Inclut la vue pour afficher la liste et, le cas échéant, le formulaire
require_once 'vue/vueLigues.php';
