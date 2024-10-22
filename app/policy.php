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
        <?= fetchNav($_SESSION, $companies) ?>
    </nav>

    <main class="container">
        <div class="card card__legal">
            <h2 class="ttl lineUp">Politique de confidentialité</h2>

            <section class="card__section">
                <h3>Commpass est un outil de communication et de budgeting destiné à faciliter la gestion de votre entreprise. Nous respectons votre vie privée et vos données personnelles. Voici les principes de notre politique de confidentialité:</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate nesciunt accusantium esse vero quibusdam. Veniam asperiores maiores amet doloribus deleniti molestiae placeat magni? Iste omnis ut consequuntur illo quod aperiam!</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate nesciunt accusantium esse vero quibusdam. Veniam asperiores maiores amet doloribus deleniti molestiae placeat magni? Iste omnis ut consequuntur illo quod aperiam!</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate nesciunt accusantium esse vero quibusdam. Veniam asperiores maiores amet doloribus deleniti molestiae placeat magni? Iste omnis ut consequuntur illo quod aperiam!</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate nesciunt accusantium esse vero quibusdam. Veniam asperiores maiores amet doloribus deleniti molestiae placeat magni? Iste omnis ut consequuntur illo quod aperiam!</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate nesciunt accusantium esse vero quibusdam. Veniam asperiores maiores amet doloribus deleniti molestiae placeat magni? Iste omnis ut consequuntur illo quod aperiam!</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate nesciunt accusantium esse vero quibusdam. Veniam asperiores maiores amet doloribus deleniti molestiae placeat magni? Iste omnis ut consequuntur illo quod aperiam!</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate nesciunt accusantium esse vero quibusdam. Veniam asperiores maiores amet doloribus deleniti molestiae placeat magni? Iste omnis ut consequuntur illo quod aperiam!</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate nesciunt accusantium esse vero quibusdam. Veniam asperiores maiores amet doloribus deleniti molestiae placeat magni? Iste omnis ut consequuntur illo quod aperiam!</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate nesciunt accusantium esse vero quibusdam. Veniam asperiores maiores amet doloribus deleniti molestiae placeat magni? Iste omnis ut consequuntur illo quod aperiam!</p>
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