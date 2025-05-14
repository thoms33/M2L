<?php ?>
<div class="conteneur">
  <header>
    <?php include 'haut.php'; ?>
  </header>
  <main>
    <div class="bloc-section">
      <div class="titre-section">Demandes des utilisateurs</div>

      <!-- 1) Les boutons d’action -->
      <div class="actions-demandes">
        <?php if (in_array($_SESSION['typeUser'], ['benevole','salarie'])): ?>
          <form method="post" action="index.php?m2lMP=demandes">
            <input type="submit" name="btnNouvelleDemande" value="Créer une demande" class="btn-action"/>
            <input type="submit" name="btnVoirDemandes"   value="Voir mes demandes"   class="btn-action"/>
          </form>
        <?php else: /* RH */ ?>
          <form method="post" action="index.php?m2lMP=demandes">
            <input type="submit" name="btnVoirDemandes" value="Voir les demandes" class="btn-action"/>
          </form>
        <?php endif; ?>
      </div>

      <?php
      // --- 1bis) Mode intervenant + création de demande ---
      if (in_array($_SESSION['typeUser'], ['benevole','salarie'])
          && isset($_POST['btnNouvelleDemande'])):
      ?>
        <div class="bloc-info">
          <h2>Nouvelle demande de formation</h2>
          <?php $MainBody->afficherFormulaire(); ?>
        </div>

      <?php else: ?>
        <!-- 2) Grille de cartes -->
        <div class="grid-blocs">

          <?php if (isset($utilisateurs) && $utilisateurs): ?>
            <!-- RH étape 1 : liste des utilisateurs -->
            <?php foreach ($utilisateurs as $u): ?>
              <a href="index.php?m2lMP=demandes&user=<?= $u['idUser'] ?>"
                 class="bloc-item">
                <?= htmlspecialchars($u['nom'].' '.$u['prenom']) ?>
              </a>
            <?php endforeach; ?>

          <?php elseif (isset($demandes) && $demandes): ?>
            <?php if ($_SESSION['typeUser'] === 'rh'): ?>
              <!-- RH étape 2 : liste des demandes d’un utilisateur (liens cliquables) -->
              <?php foreach ($demandes as $d):
                $titre = formationsDAO::getFormationById($d['idFormation'])['intitule'];
              ?>
                <a href="index.php?m2lMP=demandes&idUtilisateurRhSelectionuser=<?= $d['idFormation'] ?>"
                   class="bloc-item">
                  <strong><?= htmlspecialchars($titre) ?></strong><br/>
                  État&nbsp;: <?= htmlspecialchars($d['etat']) ?>
                </a>
              <?php endforeach; ?>
            <?php else: ?>
              <!-- Intervenant : ses propres demandes (non-cliquables) -->
              <?php foreach ($demandes as $d):
                $titre = formationsDAO::getFormationById($d['idFormation'])['intitule'];
              ?>
                <div class="bloc-item">
                  <strong><?= htmlspecialchars($titre) ?></strong><br/>
                  État&nbsp;: <?= htmlspecialchars($d['etat']) ?>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>

          <?php else: ?>
            <p>Aucune donnée à afficher.</p>
          <?php endif; ?>

        </div>

        <!-- 3) Détails + actions (RH, étape 3) -->
        <?php if ($_SESSION['typeUser'] === 'rh' && isset($SelectedBody)): ?>
          <div class="bloc-info">
            <h2>Détails de la demande</h2>
            <?php $SelectedBody->afficherFormulaire(); ?>
          </div>
        <?php endif; ?>

      <?php endif; ?>

    </div>
  </main>
  <footer>
    <?php include 'bas.php'; ?>
  </footer>
</div>
