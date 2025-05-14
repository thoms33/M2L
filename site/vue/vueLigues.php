<div class="conteneur">
    <header>
        <?php include 'haut.php'; ?>
    </header>

    <main>
        <div class="bloc-section">
            <!-- Titre de la section -->
            <div class="titre-section">Liste des ligues</div>

            <!-- 1. Affichage de toutes les ligues sous forme de “boutons” -->
            <div class="grid-blocs">
                <?php foreach ($ligues as $ligue): ?>
                    <a
                        href="index.php?m2lMP=ligues&ligue=<?= $ligue['idLigue'] ?>"
                        class="bloc-item"
                    >
                        <?= htmlspecialchars($ligue['nomLigue']) /* Nom de la ligue */ ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- 2. Si une ligue est sélectionnée, on affiche son formulaire de détails -->
            <?php if (isset($SecondaryBody)): ?>
                <div class="bloc-info">
                    <h2>Détails de la ligue</h2>
                    <?php $SecondaryBody->afficherFormulaire(); ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <?php include 'bas.php';  ?>
    </footer>
</div>
