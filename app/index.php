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

generateToken();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?= fetchHead('WellComm'); ?>
</head>

<body>

    <header class="header">
        <?php
        echo fetchIndexHeader();
        echo fetchLogInForm($_SESSION);
        ?>
    </header>

    <nav class="nav hamburger__menu" id="menu" aria-label="Navigation principale du site">
        <ul class="nav__lst" id="nav-list">
            <li class="nav__itm">
                <a href="#connection-form" id="connection-menu" class="nav__lnk">Se connecter</a>
            </li>
            <li class="nav__itm">
                <a href="contact.php" class="nav__lnk">Nous contacter</a>
            </li>
        </ul>
    </nav>

    <main class="container container__flex">
        <div class="notifs">
            <?php
            echo getErrorMessage($errors);
            echo getSuccessMessage($messages);
            ?>
        </div>

        <div class="card">
            <h2 class="ttl" id="take-control">
                Prenez le contrôle<br>
                de votre budget<br>
                <span class="ttl--tertiary">communication</span>
            </h2>
            <section class="section" aria-labelledby="take-control">
                <img src="img/working-team.webp" alt="Équipe travaillant avec des graphiques">
                <p class="paragraph">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum nunc nulla, rutrum at lacinia ut, bibendum eget lectus. Duis tortor diam, aliquet a ullamcorper vel, sagittis ac urna. Donec et faucibus nisi. Suspendisse in velit purus. Aenean sodales nunc nisi, id euismod lorem semper ultrices. Nulla vulputate blandit orci, congue tempus purus gravida ultricies.</p>
            </section>
        </div>

        <div class="card">
            <h2 class="ttl" id="report">
                <span class="ttl--tertiary">Comptes rendus<br></span>
                EN TEMPS RÉEL
            </h2>
            <section class="section" aria-labelledby="report">
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
<script type="module" src="js/password.js"></script>

</html>