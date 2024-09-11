<?php
session_start();

require_once "includes/_config.php";
require_once "includes/_database.php";
require_once "includes/_functions.php";
require_once "includes/_security.php";
require_once "includes/_message.php";
require_once "includes/templates/_head.php";
require_once "includes/templates/_header.php";
require_once "includes/templates/_footer.php";
require_once "includes/templates/_nav.php";

generateToken();
// checkConnection($_SESSION);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?= fetchHead('WellComm'); ?>
</head>

<body>

    <header class="header">
        <?php
        echo fetchHeader('dashboard.php', 'Mon tableau de bord');
        ?>
    </header>

    <nav class="nav hamburger__menu" id="menu" aria-label="Navigation principale du site">
        <?= fetchNav() ?>
    </nav>

    <main class="container container__flex">
        <h2 class="ttl">
            Bonjour Julie
            <span class="ttl--tertiary">FakeBusiness</span>
        </h2>

        <section class="card campaign">
            <h2 class="ttl ttl--secondary">
                Campagnes 2024
            </h2>
            <div class="card__section">
                <div class="campaign__ttl">
                    <h3 class="ttl ttl--small">Campagne Truc</h3>
                    <!-- H4 UNIQUEMENT POUR TOILE DE COM  -->
                    <h4 class="ttl ttl--small ttl--small-lowercase">FakeBusiness</h4>
                </div>
                <div class="campaign__stats">
                    <img src="img/chart.webp" alt="Graphique camembert récapitulatif d'une campagne">

                    <div class="vignettes-section">
                        <div class="vignette vignette--primary">
                            <h4 class="vignette__ttl">
                                Budget attribué
                            </h4>
                            <p class="vignette__price">28 000 €</p>
                        </div>
                        <div class="vignette vignette--secondary">
                            <h4 class="vignette__ttl">
                                Budget dépensé
                            </h4>
                            <p class="vignette__price">13 562.05 €</p>
                        </div>
                        <div class="vignette vignette--tertiary">
                            <h4 class="vignette__ttl">
                                Budget restant
                            </h4>
                            <p class="vignette__price">14 437.95 €</p>
                        </div>
                    </div>
                </div>
                <div class="campaign__legend-section">
                    <p class="campaign__legend">Lumosphère</p>
                    <p class="campaign__legend">Vélocitix</p>
                    <p class="campaign__legend">Stellar Threads</p>
                    <p class="campaign__legend">Aurélys</p>
                    <p class="campaign__legend">Toutes les marques</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <?= fetchFooter() ?>
    </footer>
</body>

<script type="module" src="js/script.js"></script>
<script type="module" src="js/password.js"></script>

</html>