<?php
require_once __DIR__ . '/../modeles/dao/dBConnex.php'; // on met DIR pour gagner en solidité
require_once __DIR__ . '/../modeles/dao/utilisateurDAO.php';
require_once __DIR__ . '/../modeles/dao/formationsDAO.php';
require_once __DIR__ . '/../modeles/dao/demandesDAO.php';

// On force la page “Demandes” quand RH clique sur un utilisateur
if (isset($_GET['user'])) {
    $_GET['idUtilisateurRhSelection'] = $_GET['user'];
    $_SESSION['m2lMP'] = 'Demandes';
}

$demandes     = null;
$utilisateurs = null;
$SelectedBody = null;

/* 0bis) RH → suppression définitive d’une demande */
if (isset($_POST['btnSupprimerDemandeRh'])
    && $_SESSION['typeUser'] === 'rh'
    && isset($_POST['idFormation'])
    && isset($_SESSION['idUtilisateurSelectionne'])
) {
    // supprime en base et redirige vers la liste RH
    demandesDAO::supprimerDemande(
        intval($_POST['idFormation']),
        intval($_SESSION['idUtilisateurSelectionne'])
    );
    header('Location: index.php?m2lMP=demandes');
    exit;
}

// 1) Clic “Voir les demandes”
if (isset($_POST['btnVoirDemandes'])) {
    // 1a) Intervenant : sa propre liste
    if (in_array($_SESSION['typeUser'], ['benevole','salarie'])) {
        $demandes = DemandesDAO::getDemandes($_SESSION['identification']);
        $MainBody = new Formulaire('post','index.php','demandes','demandes');
        foreach ($demandes as $d) {
            // Titre + état
            $titre = formationsDAO::getFormationById($d['idFormation'])['intitule'];
            $MainBody->ajouterComposantLigne(
                $MainBody->creerInputTexte('nomFormation','nomFormation',$titre,0,'','')
            );
            $MainBody->ajouterComposantTab();
            $MainBody->ajouterComposantLigne(
                $MainBody->creerInputTexte('etat','etat',$d['etat'],0,'','',true,5)
            );
            $MainBody->ajouterComposantTab();
            // Supprimer sa demande
            $MainBody->ajouterComposantLigne(
                $MainBody->creerInputSubmit('btnSupprimerDemande','btnSupprimerDemande','Supprimer')
            );
            $MainBody->ajouterComposantTab();
            // ID caché pour suppression
            $MainBody->ajouterComposantLigne(
                $MainBody->creerInputTexte('idFormation','idFormation',$d['idFormation'],0,'','',true)
            );
            $MainBody->ajouterComposantTab();
        }
        // Bouton Retour
        $MainBody->ajouterComposantLigne(
            $MainBody->creerInputSubmit('demandes','demandes','Retour')
        );
        $MainBody->ajouterComposantTab();
        $MainBody->creerFormulaire();

    // 1b) RH : liste des utilisateurs qui ont fait au moins une demande
    } else {
        $utilisateurs = UtilisateurDAO::getUtilisateursAvecDemande();
        $MainBody = new Menu('demandes');
        foreach ($utilisateurs as $u) {
            $MainBody->ajouterComposant(
                $MainBody->creerItemLien($u['idUser'],$u['nom'].' '.$u['prenom'])
            );
        }
        $MainBody = $MainBody->creerMenu(0,'idUtilisateurRhSelection');
    }

// 2) RH → sélection d’un utilisateur
} elseif (isset($_GET['idUtilisateurRhSelection'])) {
    $_SESSION['idUtilisateurSelectionne'] = $_GET['idUtilisateurRhSelection'];
    $demandes = DemandesDAO::getDemandesUser($_SESSION['idUtilisateurSelectionne']);
    $MainBody = new Menu('demandesUser');
    foreach ($demandes as $d) {
        $MainBody->ajouterComposant(
            $MainBody->creerItemLien(
                $d['idFormation'],
                formationsDAO::getFormationById($d['idFormation'])['intitule']
            )
        );
    }
    $MainBody = $MainBody->creerMenu(0,'idUtilisateurRhSelectionuser');

// 3) RH → sélection d’une formation pour cet utilisateur
} elseif (isset($_GET['idUtilisateurRhSelectionuser'])) {
    $laDemande = DemandesDAO::getDemandesByUserAndForm(
        $_GET['idUtilisateurRhSelectionuser'],
        $_SESSION['idUtilisateurSelectionne']
    );
    // Menu secondaire
    $MainBody = new Menu('idUtilisateurRhSelectionuserForm');
    $MainBody->ajouterComposant(
        $MainBody->creerItemLien(
            $laDemande['idFormation'],
            formationsDAO::getFormationById($laDemande['idFormation'])['intitule']
        )
    );
    $MainBody = $MainBody->creerMenu(0,'idUtilisateurRhSelectionuserForm');

    // Formulaire readonly + boutons Valider/Refuser
    $SelectedBody = new Formulaire('post','index.php','demandes','demandes');
    $SelectedBody->ajouterComposantLigne(
        $SelectedBody->creerInputTexte(
            'nomFormation','nomFormation',
            formationsDAO::getFormationById($laDemande['idFormation'])['intitule'],
            0,'',''
        )
    );
    $SelectedBody->ajouterComposantTab();
    $SelectedBody->ajouterComposantLigne(
        $SelectedBody->creerInputTexte('etat','etat',$laDemande['etat'],0,'','',true,5)
    );
    $SelectedBody->ajouterComposantTab();
    // ID caché
    $SelectedBody->ajouterComposantLigne(
        $SelectedBody->creerInputTexte('idFormation','idFormation',$laDemande['idFormation'],0,'','',true)
    );
    $SelectedBody->ajouterComposantTab();
    // Acceptation / refus
    $SelectedBody->ajouterComposantLigne(
        $SelectedBody->creerInputSubmit('btnAccepterDemande','btnAccepterDemande','Valider')
    );
    $SelectedBody->ajouterComposantLigne(
        $SelectedBody->creerInputSubmit('refidUtilisateurSelectionne','refidUtilisateurSelectionne','Refuser')
    );
    $SelectedBody->ajouterComposantTab();
    $SelectedBody->creerFormulaire();

// 4) Intervenant → suppression d’une de ses demandes
} elseif (isset($_POST['btnSupprimerDemande'])) {
    $u = utilisateurDAO::getIdUtilisateur($_SESSION['identification']);
    demandesDAO::supprimerDemande(
        intval($_POST['idFormation']),
        intval($u['idUser'])
    );
    $_POST['btnVoirDemandes'] = true; // rechargement

// 5) Intervenant → formulaire de nouvelle demande
} elseif (isset($_POST['btnNouvelleDemande'])) {
    $ouvertes = formationsDAO::recupererFormationsOuvertes();
    $choix = [];
    foreach ($ouvertes as $f) {
        $choix[$f['intitule']] = $f['idFormation'];
    }
    $MainBody = new Formulaire('post','index.php','demandes','demandes');
    $MainBody->ajouterComposantLigne($MainBody->creerLabel('Créer une demande'));
    $MainBody->ajouterComposantTab();
    $MainBody->ajouterComposantLigne(
        $MainBody->creerSelect('descriptionDemande','descriptionDemande','',$choix)
    );
    $MainBody->ajouterComposantTab();
    $MainBody->ajouterComposantLigne(
        $MainBody->creerInputSubmit('btnValiderDemande','btnValiderDemande','Valider')
    );
    $MainBody->ajouterComposantLigne(
        $MainBody->creerInputSubmit('btnVoirDemandes','btnVoirDemandes','Retour')
    );
    $MainBody->ajouterComposantTab();
    $MainBody->creerFormulaire();

// 6) Intervenant → enregistrement de la nouvelle demande
} elseif (isset($_POST['btnValiderDemande'])) {
    $u = utilisateurDAO::getIdUtilisateur($_SESSION['identification']);
    demandesDAO::btnNouvelleDemande(
        intval($_POST['descriptionDemande']),
        intval($u['idUser'])
    );
    $_POST['btnVoirDemandes'] = true;

// 7) RH → refus d’une demande
} elseif (isset($_POST['refidUtilisateurSelectionne'])) {
    $idForm = intval($_POST['idFormation']);
    demandesDAO::modifierStatutDemande(
        'refusée',
        $idForm,
        $_SESSION['idUtilisateurSelectionne']
    );

// 8) RH → validation (contrôle capacitaire)
} elseif (isset($_POST['btnAccepterDemande'])) {
    $idForm = intval($_POST['idFormation']);
    $max   = formationsDAO::getFormationById($idForm)['capaciteMax'];
    $count = demandesDAO::countAcceptedForFormation($idForm);
    if ($count < $max) {
        demandesDAO::modifierStatutDemande(
            'validée',
            $idForm,
            $_SESSION['idUtilisateurSelectionne']
        );
    } else {
        $_SESSION['errorDemandes'] = "Capacité atteinte ({$max})";
    }

// 9) Par défaut : boutons d’entrée
} else {
    $MainBody = new Formulaire('post','index.php','demandes','demandes');
    if (in_array($_SESSION['typeUser'], ['benevole','salarie'])) {
        $MainBody->ajouterComposantLigne(
            $MainBody->creerInputSubmit('btnNouvelleDemande','btnNouvelleDemande','Créer une demande')
        );
        $MainBody->ajouterComposantLigne(
            $MainBody->creerInputSubmit('btnVoirDemandes','btnVoirDemandes','Voir mes demandes')
        );
    } else {
        $MainBody->ajouterComposantLigne(
            $MainBody->creerInputSubmit('btnVoirDemandes','btnVoirDemandes','Voir les demandes')
        );
    }
    $MainBody->ajouterComposantTab();
    $MainBody->creerFormulaire();
}

// Affichage de la vue
require_once __DIR__ . '/../vue/vueDemandes.php';
