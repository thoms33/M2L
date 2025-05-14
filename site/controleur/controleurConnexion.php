<?php

// Si l'utilisateur n'est pas authentifié, affiche le formulaire de connexion
if (!isset($_SESSION['identification']) || !$_SESSION['identification']) {

    // Formulaire POST vers index.php pour la connexion
    $formulaireConnexion = new Formulaire('post', 'index.php', 'fConnexion', 'fConnexion');
    
    // Champ Identifiant
    $formulaireConnexion->ajouterComposantLigne(
        $formulaireConnexion->creerLabel('Identifiant :')
    );
    $formulaireConnexion->ajouterComposantLigne(
        $formulaireConnexion->creerInputTexte(
            'login', 'login', '', 
            1, 'Entrez votre identifiant', '', 
            false
        )
    );
    $formulaireConnexion->ajouterComposantTab();

    // Champ Mot de passe
    $formulaireConnexion->ajouterComposantLigne(
        $formulaireConnexion->creerLabel('Mot de Passe :')
    );
    $formulaireConnexion->ajouterComposantLigne(
        $formulaireConnexion->creerInputMdp(
            'mdp', 'mdp', 
            1, 'Entrez votre mot de passe', '', 
            false
        )
    );
    $formulaireConnexion->ajouterComposantTab();

    // Bouton Valider
    $formulaireConnexion->ajouterComposantLigne(
        $formulaireConnexion->creerInputSubmit('submitConnex', 'submitConnex', 'Valider')
    );
    $formulaireConnexion->ajouterComposantTab();

    // Affiche un éventuel message d'erreur
    $formulaireConnexion->ajouterComposantLigne(
        $formulaireConnexion->creerMessage($messageErreurConnexion)
    );
    $formulaireConnexion->ajouterComposantTab();

    // Génère et affiche le formulaire
    $formulaireConnexion->creerFormulaire();
    require_once 'vue/vueConnexion.php';

} else {
    // Si déjà connecté, remet à zéro la session et redirige vers l'accueil
    $_SESSION['typeUser']       = [];
    $_SESSION['identification'] = [];
    $_SESSION['m2lMP']          = 'accueil';

    header('Location: index.php');
    exit;
}
