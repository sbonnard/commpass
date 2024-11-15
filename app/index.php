<?php
session_start();

// REDIRIGE L'UTILISATEUR SUR LE SITE EN HTTPS SI NON-RENSEIGNÉ DANS L'URL.
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit;
}

//CONFIG AND CONNECTION
require_once "includes/_config.php";
require_once "includes/_database.php";

// FUNCTIONS
require_once "includes/_functions.php";
require_once "includes/_security.php";
require_once "includes/_message.php";

// DATAS
require_once "includes/_datas.php";

// TEMPLATES
require_once "includes/templates/_head.php";
require_once "includes/templates/_header.php";
require_once "includes/templates/_forms.php";
require_once "includes/templates/_footer.php";
require_once "includes/templates/_nav.php";

generateToken();

if (isset($_SESSION) && !empty($_SESSION) && isset($_SESSION['id_user'], $_SESSION['username'], $_SESSION['client'], $_SESSION['boss'], $_SESSION['id_company'])) {
    header('Location: dashboard');
}

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
        echo fetchHeader('#');
        echo fetchLogInForm($_SESSION);
        ?>
    </header>

    <nav class="nav hamburger__menu" id="menu" aria-label="Navigation principale du site">
        <?= fetchNav($_SESSION, $companies) ?>
    </nav>

    <div class="notifs">
        <?php
        echo getErrorMessage($errors);
        echo getSuccessMessage($messages);
        ?>
    </div>

    <main class="container container__flex">

        <div class="card" data-card="">
            <h2 class="ttl lineUp" id="take-control" aria-hidden="true">
                Prenez le contrôle<br>
                de votre budget<br>
                <span class="ttl--tertiary">communication</span>
            </h2>
            <section class="card__section" aria-labelledby="take-control">
                <div class="compass">
                    <img src="../img/compass-border.svg" alt="Une boussole rose">
                    <img class="compass__arrow" src="../img/compass-arrow.svg" alt="Flèche de boussole" aria-hidden="true">
                </div>
                <p class="paragraph">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum nunc nulla, rutrum at lacinia ut, bibendum eget lectus. Duis tortor diam, aliquet a ullamcorper vel, sagittis ac urna. Donec et faucibus nisi. Suspendisse in velit purus. Aenean sodales nunc nisi, id euismod lorem semper ultrices. Nulla vulputate blandit orci, congue tempus purus gravida ultricies.</p>
            </section>
        </div>

        <div class="card" data-card="">
            <h2 class="ttl lineUp" id="report" aria-hidden="true">
                <span class="ttl--tertiary">Comptes rendus<br></span>
                EN TEMPS RÉEL
            </h2>
            <section class="card__section" aria-labelledby="report">
                <img src="img/working-team.webp" alt="Équipe travaillant avec des graphiques">
                <p class="paragraph">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum nunc nulla, rutrum at lacinia ut, bibendum eget lectus. Duis tortor diam, aliquet a ullamcorper vel, sagittis ac urna. Donec et faucibus nisi. Suspendisse in velit purus. Aenean sodales nunc nisi, id euismod lorem semper ultrices. Nulla vulputate blandit orci, congue tempus purus gravida ultricies.</p>
            </section>
        </div>

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