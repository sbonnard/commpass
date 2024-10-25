<?php
session_start();

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
        echo fetchHeader('index');
        echo fetchLogInForm($_SESSION);
        ?>
    </header>

    <nav class="nav hamburger__menu" id="menu" aria-label="Navigation principale du site">
        <?= fetchNav($_SESSION, $companies) ?>
    </nav>

    <main class="container">
        <div class="card">
            <h2 class="ttl lineUp">Mentions légales</h2>
            <section class="card__section card__legal">
                <h3>Éditeur de code : Visual Studio Code</h3>
                <p>Commpass est un outil de gestion de budgets et de communication, développé par <a class="text-tertiary" href="https://www.linkedin.com/in/s%C3%A9bastien-bonnard-72164a239/" target="_blank">Sébastien Bonnard</a> pour <a class="secondary" href="https://www.toiledecom.fr/" target="_blank">Toile de Com.</a></p>
                <p>Tous les droits sont réservés.</p>
                <p>Version : 1.0.0</p>
                <p>Date de création : 26/08/2024</p>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatem ex commodi, eius, saepe laborum, repudiandae ratione expedita fuga repellat illum sapiente rem dicta culpa modi similique officia quis debitis? Amet.</p>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatem ex commodi, eius, saepe laborum, repudiandae ratione expedita fuga repellat illum sapiente rem dicta culpa modi similique officia quis debitis? Amet.</p>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatem ex commodi, eius, saepe laborum, repudiandae ratione expedita fuga repellat illum sapiente rem dicta culpa modi similique officia quis debitis? Amet.</p>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatem ex commodi, eius, saepe laborum, repudiandae ratione expedita fuga repellat illum sapiente rem dicta culpa modi similique officia quis debitis? Amet.</p>
            </section>
        </div>

    </main>

    <footer class="footer">
        <?= fetchFooter() ?>
    </footer>
</body>

<script type="module" src="js/script.js"></script>

<?php
// SI L'UTILISATEUR EST CONNECTÉ, JE NE VEUX PAS QUE LE SCRIPT index.js S'APPLIQUE
if (!isset($_SESSION['id_user'])) {
    echo '<script type="module" src="js/index.js"></script>';
}
?>

<script type="module" src="js/password.js"></script>
<script type="module" src="js/cards.js"></script>
<?php
// LE SCRIPT DE DROPDOWN N'EST UTILE QUE POUR LES UTILISATEURS NON-CLIENTS
if (isset($_SESSION['client']) && $_SESSION['client'] === 0) {
    echo '<script type="module" src="js/dropdown-menu.js"></script>';
}
?>

</html>