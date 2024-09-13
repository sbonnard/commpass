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
        <?= fetchNav('nav__itm--active') ?>
    </nav>

    <main class="container container--campaigns container__flex">
        <div class="notifs">
            <?php
            echo getErrorMessage($errors);
            echo getSuccessMessage($messages);
            ?>
        </div>

        <h2 class="ttl">
            Bonjour <?= $user['firstname'] ?><br>
            <span class="ttl--tertiary"><?= getCompanyName($dbCo, $_SESSION) ?></span>
        </h2>

        <div class="button__section">
            <?php
            if (isset($_SESSION['client']) && $_SESSION['client'] === 0) {
                echo '<a href="new-campaign.php" class="button button--new-campaign" aria-label="Redirige vers un formulaire de création de campagne de com">Nouvelle campagne</a>';
            }
            ?>
            <button class="button button--filter" id="filter-button" aria-label="Ouvre un formulaire de filtres">Filtres</button>
        </div>

        <div class="hidden" id="filter-container">
            <form class="card__section form hidden" action="api.php" method="post" id="filter-form">
                <ul class="form__lst form__lst--app">
                    <li>
                        <input class="form__input form__input--from" type="date" name="date-from" id="date-from">
                    </li>
                    <li>
                        <input class="form__input form__input--to" type="date" name="date-to" id="date-to">
                    </li>
                    <input class="button button--filter button--filter--primary" type="submit" value="Filtrer" aria-label="Valider la création de la nouvelle campagne">
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                    <input type="hidden" name="action" value="filter-campaigns">
                </ul>
            </form>
        </div>

        <section class="card campaign">
            <?= getMessageIfNoCampaign($campaigns) ?>
            <?= getCampaignTemplate($dbCo, $campaigns, $brands, $_SESSION) ?>

            <?php
            // var_dump($_SESSION);
            // var_dump($brands);
            // var_dump($campaigns);
            ?>

        </section>
    </main>

    <footer class="footer">
        <?= fetchFooter() ?>
    </footer>
</body>

<script type="module" src="js/script.js"></script>
<script type="module" src="js/filter.js"></script>

</html>