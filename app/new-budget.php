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
    <?= fetchHead('WellComm'); ?>
</head>

<body>

    <header class="header">
        <?php
        echo fetchHeader('dashboard.php', 'Mon tableau de bord');
        ?>
    </header>

    <nav class="nav hamburger__menu" id="menu" aria-label="Navigation principale du site">
        <?= fetchNav($_SESSION) ?>
    </nav>

    <main class="container container--campaigns container__flex">
        <div class="notifs">
            <?php
            echo getErrorMessage($errors);
            echo getSuccessMessage($messages);
            ?>
        </div>

        <div class="card big-padding">
            <h2 class="ttl lineUp" id="new-budget-ttl">Budget
                <?php
                if (isset($_GET['myc']) && intval($_GET['myc'])) {
                    echo 'de la campagne<br>' . $selectedCampaign['campaign_name'];
                } else {
                    echo 'annuel';
                }
                ?>
                <br>
                <span class="ttl--tertiary"><?= getClientName($dbCo, $_SESSION, $selectedCampaign) ?></span>
            </h2>

            <section class="card__section" aria-labelledby="new-budget-ttl">
                <form class="form" method="post" action="actions.php">
                    <ul class="form__lst form__lst--app">
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="budget">Fixer le budget<?php
                                if (isset($_GET['myc']) && intval($_GET['myc'])) {
                                    echo '';
                                } else if(isset($_SESSION['filter']['id_company']) && intval($_SESSION['filter']['id_company'])) {
                                    echo ' annuel pour ' . $currentYear;
                                }
                            ?></label>
                            <input class="form__input" type="text" id="budget" name="budget" placeholder="35000" value="<?php
                                                                                                                        if (isset($_GET['myc']) && intval($_GET['myc'])) {
                                                                                                                            echo $selectedCampaign['budget'];
                                                                                                                        } else {
                                                                                                                            echo $companyAnnualBudget;
                                                                                                                        }
                                                                                                                        ?>" required>
                        </li>
                        <input class="button button--confirm" type="submit" value="Fixer le budget">
                    </ul>
                    <input type="hidden" name="id_company" value="<?php
                                                                    if (isset($_SESSION['filter']['id_company']) && intval($_SESSION['filter']['id_company'])) {
                                                                        echo $_SESSION['filter']['id_company'];
                                                                    } else {
                                                                        echo $selectedCampaign['id_company'];
                                                                    }
                                                                    ?>">
                    <?php
                    if (isset($_GET['myc']) && intval($_GET['myc'])) {
                        echo '<input type="hidden" name="action" value="set_campaign_budget">
                        <input type="hidden" name="myc" value="' . $_GET['myc'] . '">';
                    } else {
                        echo '<input type="hidden" name="action" value="set_annual_budget">';
                    }
                    ?>
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