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

if (!isset($_SESSION['client']) && $_SESSION['client'] === 1) {
    header('Location: dashboard.php');
    exit;
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
        echo fetchHeader('dashboard.php', 'Mon tableau de bord');
        ?>
    </header>

    <nav class="nav hamburger__menu" id="menu" aria-label="Navigation principale du site">
        <?= fetchNav() ?>
    </nav>

    <main class="container container--campaigns container__flex">
        <div class="notifs">
            <?php
            echo getErrorMessage($errors);
            echo getSuccessMessage($messages);
            ?>
        </div>

        <div class="card big-padding">
            <h2 class="ttl lineUp" id="new-campaign-ttl">Budget annuel<br>
                <span class="ttl--tertiary"><?= getClientName($dbCo, $_SESSION) ?></span>
            </h2>

            <section class="card__section" aria-labelledby="new-campaign-ttl">
                <form class="form" method="post" action="actions.php">
                    <ul class="form__lst form__lst--app">
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="annual_budget">Fixer le budget annuel pour <?= $currentYear ?></label>
                            <input class="form__input" type="text" id="annual_budget" name="annual_budget" placeholder="35000" value="<?= $companyAnnualBudget ?>" required>
                        </li>
                        <input class="button button--confirm" type="submit" value="Fixer le budget">
                    </ul>
                    <input type="hidden" name="id_company" value="<?= $_SESSION['filter']['id_company'] ?>">
                    <input type="hidden" name="action" value="set_annual_budget">
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
<script type="module" src="js/ajax.js"></script>

</html>