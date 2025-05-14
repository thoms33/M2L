<?php
// 1) Change la page affichée si paramètre m2lMP présent en URL
if (isset($_GET['m2lMP'])) {
    $_SESSION['m2lMP'] = $_GET['m2lMP'];
}
// 2) Si formulaire “demandes” soumis, reste sur la page Demandes
elseif (isset($_POST['demandes'])) {
    $_SESSION['m2lMP'] = 'Demandes';
}

// --- Gestion des demandes (RH & intervenants) ---

// 3) Refus d’une demande (RH)
elseif (isset($_POST['refidUtilisateurSelectionne'])) {
    $idForm = formationsDAO::getIdFormationByIntitule($_POST['nomFormation']);
    demandesDAO::modifierStatutDemande('refusée', $idForm, $_SESSION['idUtilisateurSelectionne']);
    $_SESSION['m2lMP'] = 'Demandes';
}
// 4) Validation d’une demande (RH)
elseif (isset($_POST['btnAccepterDemande'])) {
    $idForm = formationsDAO::getIdFormationByIntitule($_POST['nomFormation']);
    demandesDAO::modifierStatutDemande('validée', $idForm, $_SESSION['idUtilisateurSelectionne']);
    $_SESSION['m2lMP'] = 'Demandes';
}
// 5) Création de nouvelle demande (bénévole/salarié)
elseif (isset($_POST['btnValiderDemande'])) {
    $u = utilisateurDAO::getIdUtilisateur($_SESSION['identification']);
    demandesDAO::btnNouvelleDemande(
        intval($_POST['descriptionDemande']),
        intval($u['idUser'])
    );
    // redirige pour afficher la liste des demandes
    header('Location: index.php?m2lMP=demandes');
    exit;
}

// --- Gestion des formations ---

// 6) Accès à la page Formations
elseif (isset($_POST['formation'])) {
    $_SESSION['m2lMP'] = 'Formation';
}
// 7) Suppression d’une formation (RH)
elseif (isset($_POST['suppFormation'])) {
    formationsDAO::deleteFormation($_POST['nomFormation']);
    $_SESSION['m2lMP'] = 'Formation';
}
// 8) Ajout d’une formation (RH)
elseif (isset($_POST['enregFormation'])) {
    formationsDAO::addFormation(
        $_POST['nomFormation'],
        $_POST['descriptionFormation'],
        $_POST['TempsFormation'],
        $_POST['dateOuvertInscriptions'],
        $_POST['dateClotureInscriptions'],
        $_POST['capaciteMax']
    );
    $_SESSION['m2lMP'] = 'Formation';
}

// --- Gestion des clubs ---

// 9) Passage en mode “modifier un club”
elseif (isset($_GET['modifClub'])) {
    $_SESSION['isConnected'] = true;
    $_SESSION['m2lMP']       = 'clubs';
}
// 10) Accès à la page Clubs
elseif (isset($_GET['club']) || isset($_POST['club'])) {
    $_SESSION['m2lMP'] = 'clubs';
}

// --- Connexion / déconnexion ---

// 11) Traitement du formulaire de connexion
elseif (isset($_POST['submitConnex'])) {
    $isVerified = UtilisateurDAO::authentification($_POST['login'], $_POST['mdp']);
    if ($isVerified === null) {
        $_SESSION['identification'] = false;
        $_SESSION['m2lMP']          = 'connexion';
        $messageErreurConnexion     = "Personne n'est enregistré sous ces données";
    } else {
        $userInfo                   = UtilisateurDAO::getUser($_POST['login']);
        $_SESSION['identification'] = $_POST['login'];
        $_SESSION['typeUser']       = $userInfo['typeUser'];
        $_SESSION['m2lMP']          = 'accueil';
    }
}
// 12) Vue Clubs depuis menu
elseif (isset($_POST['voirClubs'])) {
    $_SESSION['m2lMP'] = 'Clubs';
}
// 13) Sélection d’une ligue
elseif (isset($_GET['ligue'])) {
    $_SESSION['ligueId'] = $_GET['ligue'];
    $_SESSION['m2lMP']   = 'ligues';
}

// 14) Par défaut : page d’accueil
else {
    if (!isset($_SESSION['m2lMP'])) {
        $_SESSION['m2lMP'] = 'accueil';
    }
}

// --- Construction du menu principal ---

$m2lMP = new Menu('m2lMP');
$m2lMP->ajouterComposant($m2lMP->creerItemLien('accueil', 'Accueil'));
$m2lMP->ajouterComposant($m2lMP->creerItemLien('services', 'Services'));
$m2lMP->ajouterComposant($m2lMP->creerItemLien('locaux',   'Locaux'));
$m2lMP->ajouterComposant($m2lMP->creerItemLien('ligues',   'Ligues'));

if (!empty($_SESSION['identification'])) {
    // intervenants et RH ont accès aux formations et demandes
    if (in_array($_SESSION['typeUser'], ['benevole', 'salarie', 'rh'])) {
        $m2lMP->ajouterComposant($m2lMP->creerItemLien('formation', 'Formations'));
        $m2lMP->ajouterComposant($m2lMP->creerItemLien('demandes',   'Demandes'));
    }
    // bouton Deconnexion
    $m2lMP->ajouterComposant($m2lMP->creerItemLien('connexion', 'Deconnexion'));
} else {
    // bouton Se connecter
    $m2lMP->ajouterComposant($m2lMP->creerItemLien('connexion', 'Se connecter'));
}

// génère et affiche le menu
$menuPrincipalM2L = $m2lMP->creerMenu($_SESSION['m2lMP'], 'm2lMP');

// inclut le contrôleur de la page active
include_once dispatcher::dispatch($_SESSION['m2lMP']);
