<div class="conteneur">
    <header>
        <?php include 'haut.php'; ?>
    </header>

    <main>
        <div class="bloc-section">
            <div class="titre-section">Intitulé de la formation</div>

            <!-- 1. Liste des formations sous forme de blocs -->
            <div class="grid-blocs">
                <?php foreach ($formations as $f): ?>
                    <a
                        href="index.php?m2lMP=formation&formation=<?= $f['idFormation'] ?>"
                        class="bloc-item">
                        <?= htmlspecialchars($f['intitule']) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- 2. Détails + actions si on a un formulaire à afficher -->
            <?php if (isset($SecondaryBody)): ?>
                <div class="bloc-info">
                    <h2>Détails de la formation</h2>

                    <!-- formulaire généré en contrôleur (lecture seule ou édition pour RH) -->
                    <?php $SecondaryBody->afficherFormulaire(); ?>

                    <!-- Actions réservées aux RH -->
                    <?php if ($_SESSION['typeUser'] === 'rh'): ?>

                        <!-- Lien “Supprimer” -->
                        <?php if (isset($_GET['formation'])):
                            $id = (int)$_GET['formation'];
                        ?>
                            <p style="text-align:center;margin-top:20px;">
                                <a
                                    href="index.php?m2lMP=formation&formation=<?= $id ?>&delete=1"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette formation ? Cette action est irréversible.');"
                                    class="btn-supprimer">
                                    Supprimer cette formation
                                </a>
                            </p>
                        <?php endif; ?>

                        <!-- Les boutons “Ajouter” et “Modifier” sont générés par le contrôleur via $SecondaryBody.
                             Si vous aviez du HTML direct ici, entourez-le également de ce test RH. -->
                        <!--
                        <?php if (isset($_GET['formation'])): ?>
                            <input type="submit" name="modifFormation" value="Modifier" class="btn-action"/>
                        <?php endif; ?>
                        <input type="submit" name="ajouterFormation" value="Ajouter une nouvelle formation" class="btn-action"/>
                        -->

                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Confirmation pour le bouton “Modifier” (POST)
                const btnModif = document.querySelector('input[name="modifFormation"]');
                if (btnModif) {
                    btnModif.addEventListener('click', function(e) {
                        if (!confirm("Êtes-vous sûr de vouloir éditer cette formation ?")) {
                            e.preventDefault();
                        }
                    });
                }
            });
        </script>
    </main>

    <footer>
        <?php include 'bas.php'; ?>
    </footer>
</div>
