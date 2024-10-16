<?php
session_start();

//CONFIG AND CONNECTION
require_once "../includes/_config.php";
require_once "../includes/_database.php";

// FUNCTIONS
require_once "../includes/_functions.php";
require_once "../includes/_security.php";
require_once "../includes/_message.php";

// TEMPLATES
require_once "../includes/templates/_head.php";
require_once "../includes/templates/_header.php";
require_once "../includes/templates/_footer.php";

generateToken();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?= fetchHeadErrorsPage('Commpass'); ?>
</head>

<header class="header">
    <a href="../index" title="Retour vers le site commpass.toiledecom.fr" aria-label="Lien vers le site">
        <h1 class="header__ttl">Commpass</h1>
    </a>
</header>

<body>

    <main class="container--error container__flex">
        <h1 class="ttl ttl--huge lineUp">404<br><span class="ttl--tertiary">Page Not Found</span></h1>
        <p class="ttl">Vous êtes égaré ?</p>
        <p class="medium-text">Cliquez sur la boussole pour retrouver votre chemin</p>
        <div class="compass">
<<<<<<< HEAD
            <a href="../index.php">
=======
            <a href="../index">
>>>>>>> master
                <img src="../img/compass-border.svg" alt="Une boussole rose">
                <img class="compass__arrow" src="../img/compass-arrow.svg" alt="Flèche de boussole">
            </a>
        </div>
    </main>

</body>

<footer class="footer--error">
    <picture>
        <source media="(min-width: 960px)" srcset="../img/wave-pc.svg">
        <img class="footer__wave" src="../img/wave.svg" alt="Vague rose">
    </picture>
</footer>

</html>

<script type="module" src="../js/script.js"></script>
<script type="module" src="../js/burger.js"></script>
<script type="module" src="../js/dropdown-menu.js"></script>
<script type="module" src="../js/password.js"></script>