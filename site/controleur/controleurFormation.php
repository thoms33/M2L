<?php
// 0) Suppression « GET + delete » en tout début : réservé aux RH
if (isset($_GET['formation'], $_GET['delete'])) {
    if ($_SESSION['typeUser'] === 'rh') {
        formationsDAO::deleteFormation((int)$_GET['formation']);
    }
    header('Location: index.php?m2lMP=formation');
    exit;
}

// 1) Récupère toutes les formations pour la liste (accessible à tous)
$formations = formationsDAO::getFormations();

// 2) Tout ce qui suit est réservé aux RH
if ($_SESSION['typeUser'] === 'rh') {

    // 2a) Clic sur “Ajouter une nouvelle formation” → formulaire vierge
    if (isset($_POST['ajouterFormation'])) {
        $SecondaryBody = new Formulaire(
            'post',
            'index.php?m2lMP=formation',
            'formation',
            'formation'
        );
        $fields = [
            'intitule'               => 'Intitulé de la formation :',
            'description'            => 'Description :',
            'duree'                  => 'Durée :',
            'dateOuvertInscriptions' => "Date d'ouverture des inscriptions :",
            'dateClotureInscriptions'=> "Date de clôture des inscriptions :",
            'capaciteMax'            => 'Capacité maximale :'
        ];
        foreach ($fields as $key => $label) {
            // Champ label + input vide
            $SecondaryBody->ajouterComposantLigne($SecondaryBody->creerLabel($label));
            $SecondaryBody->ajouterComposantLigne(
                $SecondaryBody->creerInputTexte($key, $key, '', 0, '', '', false)
            );
            $SecondaryBody->ajouterComposantTab();
        }
        // Boutons Enregistrer / Annuler
        $SecondaryBody->ajouterComposantLigne(
            $SecondaryBody->creerInputSubmit('enregistrerFormation','enregistrerFormation','Enregistrer')
        );
        $SecondaryBody->ajouterComposantLigne(
            $SecondaryBody->creerInputSubmit('annuler','annuler','Annuler')
        );
        $SecondaryBody->ajouterComposantTab();
        $SecondaryBody->creerFormulaire();
    }

    // 2b) Clic sur “Enregistrer” → insertion en base
    elseif (isset($_POST['enregistrerFormation'])) {
        formationsDAO::addFormation(
            $_POST['intitule'],
            $_POST['description'],
            $_POST['duree'],
            $_POST['dateOuvertInscriptions'],
            $_POST['dateClotureInscriptions'],
            $_POST['capaciteMax']
        );
        header('Location: index.php?m2lMP=formation');
        exit;
    }

    // 2c) Clic sur “Modifier” → formulaire pré-rempli
    elseif (isset($_POST['modifFormation']) && isset($_GET['formation'])) {
        $id   = (int)$_GET['formation'];
        $info = formationsDAO::getFormationById($id);

        $SecondaryBody = new Formulaire(
            'post',
            'index.php?m2lMP=formation&formation=' . $id,
            'formation',
            'formation'
        );
        // Champ caché pour conserver l’ID
        $SecondaryBody->ajouterComposantLigne(
            $SecondaryBody->creerInputTexte('formation','formation',$id,0,'','',true)
        );
        $SecondaryBody->ajouterComposantTab();

        // Champs pré-remplis
        $fields = [
            'intitule'               => 'Intitulé de la formation :',
            'description'            => 'Description :',
            'duree'                  => 'Durée :',
            'dateOuvertInscriptions' => "Date d'ouverture des inscriptions :",
            'dateClotureInscriptions'=> "Date de clôture des inscriptions :",
            'capaciteMax'            => 'Capacité maximale :'
        ];
        foreach ($fields as $key => $label) {
            $SecondaryBody->ajouterComposantLigne($SecondaryBody->creerLabel($label));
            $SecondaryBody->ajouterComposantLigne(
                $SecondaryBody->creerInputTexte($key,$key,$info[$key],0,'','',false)
            );
            $SecondaryBody->ajouterComposantTab();
        }
        // Boutons Mettre à jour / Annuler
        $SecondaryBody->ajouterComposantLigne(
            $SecondaryBody->creerInputSubmit('majFormation','majFormation','Mettre à jour')
        );
        $SecondaryBody->ajouterComposantLigne(
            $SecondaryBody->creerInputSubmit('annuler','annuler','Annuler')
        );
        $SecondaryBody->ajouterComposantTab();
        $SecondaryBody->creerFormulaire();
    }

    // 2d) Clic sur “Mettre à jour” → update en base
    elseif (isset($_POST['majFormation']) && isset($_POST['formation'])) {
        $id = (int)$_POST['formation'];
        formationsDAO::updateFormation(
            $id,
            $_POST['intitule'],
            $_POST['duree'],
            $_POST['dateOuvertInscriptions'],
            $_POST['dateClotureInscriptions'],
            $_POST['capaciteMax']
        );
        header('Location: index.php?m2lMP=formation&formation=' . $id);
        exit;
    }
}
// fin du bloc RH

// 3) Sélection d’une formation (lecture seule pour tous)
if (isset($_GET['formation'])) {
    $id   = (int)$_GET['formation'];
    $info = formationsDAO::getFormationById($id);

    $SecondaryBody = new Formulaire(
        'post',
        'index.php?m2lMP=formation&formation=' . $id,
        'formation',
        'formation'
    );
    // Champ caché ID
    $SecondaryBody->ajouterComposantLigne(
        $SecondaryBody->creerInputTexte('formation','formation',$id,0,'','',true)
    );
    $SecondaryBody->ajouterComposantTab();

    // Champs en lecture seule
    $fields = [
        'intitule'               => 'Intitulé de la formation :',
        'description'            => 'Description :',
        'duree'                  => 'Durée :',
        'dateOuvertInscriptions' => "Date d'ouverture des inscriptions :",
        'dateClotureInscriptions'=> "Date de clôture des inscriptions :",
        'capaciteMax'            => 'Capacité maximale :'
    ];
    foreach ($fields as $key => $label) {
        $SecondaryBody->ajouterComposantLigne($SecondaryBody->creerLabel($label));
        $SecondaryBody->ajouterComposantLigne(
            $SecondaryBody->creerInputTexte($key,$key,$info[$key],0,'','',true)
        );
        $SecondaryBody->ajouterComposantTab();
    }

    // 4) Boutons “Ajouter” et “Modifier” seulement pour RH
    if ($_SESSION['typeUser'] === 'rh') {
        $SecondaryBody->ajouterComposantLigne(
            $SecondaryBody->creerInputSubmit('ajouterFormation','ajouterFormation','Ajouter une nouvelle formation')
        );
        $SecondaryBody->ajouterComposantLigne(
            $SecondaryBody->creerInputSubmit('modifFormation','modifFormation','Modifier')
        );
        $SecondaryBody->ajouterComposantTab();
    }
    $SecondaryBody->creerFormulaire();
}

// 5) Appelle la vue
require_once 'vue/vueFormations.php';
