<?php
session_start();

require_once "includes/templates/_head.php";
require_once "includes/templates/_header.php";
require_once "includes/templates/_footer.php";
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
        echo fetchLogInForm();
        ?>
    </header>

    <main class="container container__flex">
        <h2 class="ttl" id="take-control">
            Prenez le contrôle
            de votre budget
            <span class="ttl--tertiary">communication</span>
        </h2>
        <section class="section" aria-labelledby="take-control">
            <img src="img/working-team.webp" alt="Équipe travaillant avec des graphiques">
            <p class="paragraph">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum nunc nulla, rutrum at lacinia ut, bibendum eget lectus. Duis tortor diam, aliquet a ullamcorper vel, sagittis ac urna. Donec et faucibus nisi. Suspendisse in velit purus. Aenean sodales nunc nisi, id euismod lorem semper ultrices. Nulla vulputate blandit orci, congue tempus purus gravida ultricies.</p>
        </section>

        <h2 class="ttl" id="report">
            <span class="ttl--tertiary">Comptes rendus</span>
            EN TEMPS RÉEL
        </h2>
        <section class="section" aria-labelledby="report">
            <img src="img/working-team.webp" alt="Équipe travaillant avec des graphiques">
            <p class="paragraph">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum nunc nulla, rutrum at lacinia ut, bibendum eget lectus. Duis tortor diam, aliquet a ullamcorper vel, sagittis ac urna. Donec et faucibus nisi. Suspendisse in velit purus. Aenean sodales nunc nisi, id euismod lorem semper ultrices. Nulla vulputate blandit orci, congue tempus purus gravida ultricies.</p>
        </section>

    </main>

    <footer class="footer">
        <?= fetchFooter() ?>
    </footer>
</body>

<script type="module" src="js/script.js"></script>
<script type="module" src="js/password.js"></script>

</html>