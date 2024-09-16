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
        <?= fetchNav() ?>
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

        <div class="card">
            <section class="card__section">
                <p class="ttl--smaller">Campagne : <?= $selectedCampaign['campaign_name'] ?></p>
                <p class="campaign__interlocutor">Interlocuteur : <?= $selectedCampaign['firstname'] . ' ' . $selectedCampaign['lastname'] ?></p>
            </section>
        </div>

        <div class="button__section">
            <button class="button button--filter" id="filter-button" aria-label="Ouvre un formulaire de filtres">Filtres</button>
        </div>

        <div class="hidden" id="filter-container">
            <form class="card__section form hidden" action="api.php" method="post" id="filter-form" aria-label="Formulaire pour filtrer les campagnes">
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

        <h2 class="ttl">Données globales</h2>

        <?php
        // var_dump(getMonthlyCampaignOperations($dbCo, $_GET, '2024-06'));
        ?>

        <div class="card">
            <section class="card__section card__section--vignettes">
                <div class="campaign__stats">
                    <div class="vignettes-section vignettes-section--big">
                        <div class="vignette vignette--primary vignette--big">
                            <h4 class="vignette__ttl vignette__ttl--big">
                                Budget attribué
                            </h4>
                            <p class="vignette__price vignette__price--big"><?= formatPrice($selectedCampaign['budget'], "€") ?></p>
                        </div>
                        <div class="vignette vignette--secondary vignette--big">
                            <h4 class="vignette__ttl vignette__ttl--big">
                                Budget dépensé
                            </h4>
                            <p class="vignette__price vignette__price--big"><?= calculateSpentBudget($dbCo, $selectedCampaign) ?></p>
                        </div>
                        <div class="vignette vignette--tertiary vignette--big">
                            <h4 class="vignette__ttl vignette__ttl--big">
                                Budget restant
                            </h4>
                            <p class="vignette__price vignette__price--big"><?= calculateRemainingBudget($dbCo, $selectedCampaign) ?></p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="card card--grid">

            <div class="card">
                <h2 class="ttl">Répartition du budget dépensé<br> par marque</h2>
                <section class="card__section">
                    <img src="img/chart.webp" alt="Graphique camembert récapitulatif de la campagne <?= $selectedCampaign['campaign_name'] ?>">
                    <ul class="campaign__legend-section">
                        <?= getBrandsAsList($brands) ?>
                        <li class="campaign__legend">Toutes les marques</li>
                    </ul>
                </section>
            </div>
            <div class="card">
                <h2 class="ttl">Budget attribué<br> par marque</h2>
                <section class="card__section">
                    <?php
                    $campaignResults = getSpendingByBrandByCampaign($dbCo, $campaigns, $_GET);

                    $brandsSpendings = mergeResults($campaignResults);

                    echo generateTableFromDatas($brandsSpendings);
                    ?>
                </section>
            </div>
        </div>
        <div class="card">
            <section class="card__section card__section--operations">
                <ul>
                    <?= getCampaignOperationsAsList($campaignOperations) ?>
                </ul>
            </section>
        </div>
    </main>

    <footer class="footer">
        <?= fetchFooter() ?>
    </footer>
</body>

<script type="module" src="js/script.js"></script>
<script type="module" src="js/campaigns.js"></script>
<script type="module" src="js/filter.js"></script>

</html>