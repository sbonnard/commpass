<?php
session_start();

//CONFIG AND CONNECTION
require_once "includes/_config.php";
require_once "includes/_database.php";

// FUNCTIONS
require_once "includes/_functions.php";
require_once "includes/_security.php";
require_once "includes/_message.php";

// CLASSES

require_once "includes/classes/class.brand.php";
require_once "includes/classes/class.campaign.php";
require_once "includes/classes/class.company.php";
require_once "includes/classes/class.user.php";

// DATAS
require_once "includes/_datas.php";

// TEMPLATES
require_once "includes/templates/_head.php";
require_once "includes/templates/_header.php";
require_once "includes/templates/_forms.php";
require_once "includes/templates/_footer.php";
require_once "includes/templates/_nav.php";

generateToken();

checkConnection($_SESSION);

checkUserClientStatus($_SESSION);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?= fetchHead('Commpass'); ?>
</head>

<body>

    <header class="header">
        <?php
        echo fetchHeader('dashboard.php', 'Mon tableau de bord');
        ?>
    </header>

    <nav class="nav hamburger__menu" id="menu" aria-label="Navigation principale du site">
        <?= fetchNav($_SESSION, '', 'nav__itm--active') ?>
    </nav>

    <main class="container container--campaigns container__flex">
        <div class="notifs">
            <?php
            echo getErrorMessage($errors);
            echo getSuccessMessage($messages);
            ?>
        </div>

        <div class="card big-padding">
            <h2 class="ttl lineUp" id="new-budget-ttl">
                Nouvelle marque
                <br>
                <span class="ttl--tertiary"><?= getCompanyNameForNewBrand($dbCo, $_GET) ?></span>
            </h2>

            <section class="card__section" aria-labelledby="new-budget-ttl">
                <form class="form" method="post" action="actions.php">
                    <ul class="form__lst form__lst--app">
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="budget">Nom de la marque</label>
                            <input class="form__input" type="text" name="brand_name" id="brand_name" required aria-label="Entrez le nom de la nouvelle marque.">
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="color" aria-label="Sélectionner la couleur de la marque">Couleur de la marque</label>
                            <input class="form__input--colour" type="color" name="color" id="color" value="">
                        </li>
                        <input class="button button--confirm" type="submit" value="Créer la marque">
                    </ul>
                    <input type="hidden" name="id_company" value="<?= $_GET['comp'] ?>">
                    <input type="hidden" name="action" value="new_brand">
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                </form>
            </section>
        </div>

    </main>

    <footer class="footer">
        <?= fetchFooter() ?>
    </footer>
</body>

<script type="module" src="js/script.js"></script>
<script type="module" src="js/burger.js"></script>
<script type="module" src="js/dropdown-menu.js"></script>
<script type="module" src="js/ajax.js"></script>

</html>