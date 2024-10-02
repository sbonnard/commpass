<?php
session_start();

//CONFIG AND CONNECTION
require_once "includes/_config.php";
require_once "includes/_database.php";

// FUNCTIONS
require_once "includes/_functions.php";
require_once "includes/_security.php";
require_once "includes/_message.php";

// TEMPLATES
require_once "includes/templates/_head.php";
require_once "includes/templates/_header.php";
require_once "includes/templates/_forms.php";
require_once "includes/templates/_footer.php";
require_once "includes/templates/_nav.php";

generateToken();

unsetFilters($_SESSION);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?= fetchHead('Commpass'); ?>
</head>

<body>

    <header class="header">
        <?php
        echo fetchHeader('index.php');
        echo fetchLogInForm($_SESSION);
        ?>
    </header>

    <nav class="nav hamburger__menu" id="menu" aria-label="Navigation principale du site">
        <?= fetchNav($_SESSION) ?>
    </nav>

    <main class="container">
        <div class="card">
            <h2 class="ttl lineUp">Mentions légales</h2>
        </div>

        <section class="card__section card__legal">
            <h3>Éditeur de code : Visual Studio Code</h3>
            <p>Commpass est un outil de gestion de budgets et de communication, créé par <a href="https://www.linkedin.com/in/s%C3%A9bastien-bonnard-72164a239/" target="_blank">Stéphanie Roux</a> et <a href="https://www.linkedin.com/in/julien-bouvet-07b6701b5/" target="_blank">Julien Bouvet</a>.</p>
            <p>Tous les droits sont réservés.</p>
            <p>Version : 1.0.0</p>
            <p>Date de création : 26/08/2024</p>
        </section>

    </main>

    <footer class="footer">
        <?= fetchFooter() ?>
    </footer>
</body>

<script type="module" src="js/script.js"></script>
<script type="module" src="js/index.js"></script>
<script type="module" src="js/password.js"></script>
<script type="module" src="js/cards.js"></script>

</html>