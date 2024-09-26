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

if (isset($_SESSION) && !empty($_SESSION) && isset($_SESSION['id_user'], $_SESSION['username'], $_SESSION['client'], $_SESSION['boss'], $_SESSION['id_company'])) {
    header('Location: dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?= fetchHead('WellComm'); ?>
</head>

<body>

    <header class="header">
        <?php
        echo fetchHeader('#');
        echo fetchLogInForm($_SESSION);
        ?>
    </header>

    <nav class="nav hamburger__menu" id="menu" aria-label="Navigation principale du site">
        <?= fetchNav($_SESSION) ?>
    </nav>

    <div class="notifs">
        <?php
        echo getErrorMessage($errors);
        echo getSuccessMessage($messages);
        ?>
    </div>

    <main class="container container__flex">

        <div class="card" data-card="">
            <h2 class="ttl lineUp" id="take-control">
                Prenez le contrôle<br>
                de votre budget<br>
                <span class="ttl--tertiary">communication</span>
            </h2>
            <section class="card__section" aria-labelledby="take-control">
                <img src="img/working-team.webp" alt="Équipe travaillant avec des graphiques">
                <p class="paragraph">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum nunc nulla, rutrum at lacinia ut, bibendum eget lectus. Duis tortor diam, aliquet a ullamcorper vel, sagittis ac urna. Donec et faucibus nisi. Suspendisse in velit purus. Aenean sodales nunc nisi, id euismod lorem semper ultrices. Nulla vulputate blandit orci, congue tempus purus gravida ultricies.</p>
            </section>
        </div>

        <div class="card" data-card="">
            <h2 class="ttl lineUp" id="report">
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