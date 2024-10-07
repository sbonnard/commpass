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
require_once "includes/classes/class.media.php";
require_once "includes/classes/class.partner.php";

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

// if(isset($_SESSION['filter']['year'])) {
//     unset($_SESSION['filter']['year']);
// }

$campaignResults = getSpendingByBrandByCampaign($dbCo, $campaigns, $_GET);

$chartData = [];
$chartColors = [];
foreach ($campaignResults as $campaignData) {
    foreach ($campaignData as $data) {
        $campaignId = $data['id_campaign'];
        if (!isset($chartData[$campaignId])) {
            $chartData[$campaignId] = [];
            $chartColors[$campaignId] = [];
        }
        $chartData[$campaignId][] = [$data['brand_name'], $data['total_spent']];
        $chartColors[$campaignId][$data['brand_name']] = $data['legend_colour_hex'];
    }
}

$jsonChartData = json_encode($chartData);
$jsonChartColors = json_encode($chartColors);

// if (isset($_SESSION['filter']) && isset($_SESSION['filter']['id_company']) || isset($_SESSION['client']) && $_SESSION['client'] === 1) {
//     // Récupérer les dépenses annuelles par objectif
//     $targetAnnualSpendings = getAnnualSpendingsByTarget($dbCo, $_SESSION);
// }

// var_dump($_SESSION);
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
        <?= fetchNav($_SESSION, '', '', 'nav__itm--active') ?>
    </nav>

    <main class="container container--campaigns container__flex">
        <div class="notifs">
            <?php
            echo getErrorMessage($errors);
            echo getSuccessMessage($messages);
            ?>
        </div>

        <h2 class="ttl lineUp">Historique</h2>

        <?php
        if (isset($_SESSION['client']) && $_SESSION['client'] === 0) {
            echo
            '<div class="card">
                <form class="card__section" action="actions-filter.php" method="post" id="filter-form">
                    <ul class="form__lst form__lst--app">
                        <div class="form__lst--flex">
                            <li class="form__itm">
                                <label for="client-filter">Sélectionner un client</label>
                                <select class="form__input form__input--select" type="date" name="client-filter" id="client-filter" required>
                                    ' . getCompaniesAsHTMLOptions($companies) . '
                                </select>
                            </li>
                            <li class="form__itm">
                                <label class="form__label" for="year">Sélectionner une année</label>
                                <select class="form__input form__input--select" name="year" id="year" required>
                                    <option value="">- Sélectionner une année -</option>
                                    <option value="2023">2023</option>
                                    <option value="2022">2022</option>
                                    <option value="2021">2021</option>
                                </select>
                            </li>
                        </div>
                        <input type="submit" class="button button--filter" id="filter-button" aria-label="Filtrer les données entrées" value="Filtrer">
                        <input type="hidden" name="token" value="' . $_SESSION['token'] . '">
                        <input type="hidden" name="action" value="filter-history">
                </form>
                <form action="actions-filter.php" method="post" id="reinit-form">
                    <input type="submit" class="button button--reinit" id="filter-reinit" aria-label="Réinitialise tous les filtres" value="" title="Réinitialiser les filtres">
                    <input type="hidden" name="token" value="' . $_SESSION['token'] . '">
                    <input type="hidden" name="action" value="filter-reinit">
                </form>';

            echo '</div>';
        }

        if (isset($_SESSION['client']) && $_SESSION['client'] === 1) {
            echo
            '<div class="card">
                <form class="card__section" action="actions-filter.php" method="post" id="filter-form">
                    <ul class="form__lst form__lst--app">
                        <div class="form__lst--flex">
                            <li class="form__itm">
                                <label class="form__label" for="year">Sélectionner une année</label>
                                <select class="form__input form__input--select" name="year" id="year">
                                    <option value="">- Sélectionner une année -</option>
                                    <option value="2023">2023</option>
                                    <option value="2022">2022</option>
                                    <option value="2021">2021</option>
                                </select>
                            </li>
                        </div>
                        <input type="submit" class="button button--filter" id="filter-button" aria-label="Filtrer les données entrées" value="Filtrer">
                        <input type="hidden" name="token" value="' . $_SESSION['token'] . '">
                        <input type="hidden" name="action" value="filter-history">
                </form>
                </div>';
        }
        ?>

        <?php
        if (isset($_SESSION['filter']) && $_SESSION['client'] === 0) {
            echo '
            <div class="card">
            <section class="card__section card__section--row" aria-labelledby="filter-ttl">
            <h3 id="filter-ttl">Filtres appliqués :</h3>';
            if (isset($_SESSION['filter']) && isset($_SESSION['filter']['year']) && isset($_SESSION['client']) && $_SESSION['client'] === 0) {
                echo '<p class="filter__text">' . $_SESSION['filter']['year'] . '</p>';
            }
            if (isset($_SESSION['filter']['id_company'])) {
                echo '<p class="filter__text">' . getClientName($dbCo, $_SESSION);
            }
            echo '</section></div>';
        }
        ?>

        <?php
        if (isset($_SESSION['filter']['id_company'], $_SESSION['filter']['year']) && $_SESSION['filter']['year'] != '') {
            $remainingBudget = floatval($historyBudget) - floatval($historySpentBudget);

            if ($historyBudget == 0) {
                $remainingBudget = 0;
            }

            // var_dump($historySpentBudget);
            echo
            '
            <div class="card">
            <section class="card__section card__section--vignettes">
                <div class="campaign__stats">
                    <div class="vignettes-section vignettes-section--row">
                        <div class="vignette vignette--bigger vignette--primary">
                            <div class="flex-row">
                                <h4 class="vignette__ttl vignette__ttl--big">
                                    Budget attribué<br>en ' . $_SESSION['filter']['year'] . '
                                </h4>
                                 </div>
                            <p class="vignette__price vignette__price--big">' . formatPrice(floatval($historyBudget), "€") . '</p>
                        </div>
            <div class="vignette vignette--secondary vignette--big">
                <h4 class="vignette__ttl vignette__ttl--big">
                    Budget dépensé<br>en ' . $_SESSION['filter']['year'] . '
                </h4>
                <p class="vignette__price vignette__price--big">' . formatPrice(floatval($historySpentBudget), '€') . '</p>
            </div>
             <div class="vignette vignette--tertiary vignette--big <?= turnVignetteRedIfNegative(calculateRemainingBudget($dbCo, $selectedCampaign)) ?>">
                            <h4 class="vignette__ttl vignette__ttl--big">
                                Budget restant<br>en ' . $_SESSION['filter']['year'] . '
                            </h4>
                            <p class="vignette__price vignette__price--big">' . formatPrice($remainingBudget, '€') . '</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>';
        } else if (isset($_SESSION['filter']['year']) && $_SESSION['client'] === 1) {
            $remainingBudget = floatval($historyBudget) - floatval($historySpentBudget);

            if ($historyBudget == 0) {
                $remainingBudget = 0;
            }
            
            echo
            '
            <div class="card">
            <h3 class="ttl lineUp">Vos campagnes en ' . $_SESSION['filter']['year'] . '</h3>
            <section class="card__section card__section--vignettes">
                <div class="campaign__stats">
                    <div class="vignettes-section vignettes-section--row">
                        <div class="vignette vignette--bigger vignette--primary">
                            <div class="flex-row">
                                <h4 class="vignette__ttl vignette__ttl--big">
                                    Budget attribué<br>en ' . $_SESSION['filter']['year'] . '
                                </h4>
                                 </div>
                            <p class="vignette__price vignette__price--big">' . formatPrice(floatval($historyBudget), "€") . '</p>
                        </div>
            <div class="vignette vignette--secondary vignette--big">
                <h4 class="vignette__ttl vignette__ttl--big">
                    Budget dépensé<br>en ' . $_SESSION['filter']['year'] . '
                </h4>
                <p class="vignette__price vignette__price--big">' . formatPrice(floatval($historySpentBudget), '€') . '</p>
            </div>
             <div class="vignette vignette--tertiary vignette--big <?= turnVignetteRedIfNegative(calculateRemainingBudget($dbCo, $selectedCampaign)) ?>">
                            <h4 class="vignette__ttl vignette__ttl--big">
                                Budget restant<br>en ' . $_SESSION['filter']['year'] . '
                            </h4>
                            <p class="vignette__price vignette__price--big">' . formatPrice($remainingBudget, '€') . '</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>';
        }
        ?>

<section class="card campaign">
            <?= getMessageIfNoCampaign($pastYearsCampaigns, 'dans votre historique ') ?>
            <?php
            if (isset($_SESSION['client']) && $_SESSION['client'] === 1 && $_SESSION['boss'] === 1 && isset($_SESSION['filter']['year'])) {
                echo getCampaignTemplate($dbCo, $history, $_SESSION);
                // var_dump('CAS N°1');
            } else if (isset($_SESSION['client']) && $_SESSION['client'] === 1) {
                // Cas où l'utilisateur est un client
                echo getHistoryCampaignTemplateClient($dbCo, $pastYearsCampaigns, $_SESSION);
                // var_dump('CAS N°2');
            }
            // Si le filtre 'id_company' est défini, mais que c'est une session différente du client
            else if (isset($_SESSION['filter']['id_company'])) {
                $pastYearsCampaigns = getCompanyCampaignsPastYears($dbCo, $_SESSION, $campaigns);
                echo getCampaignTemplate($dbCo, $history, $_SESSION);
                // var_dump('CAS N°3');
            }
            // Cas général (pas de client et pas de filtre de company. Prend en compte le filtre 'year' si il est en place)
            else {
                echo getHistoryCampaignTemplateByCompany($dbCo, $pastYearsCampaigns, $_SESSION, $companies);
                // var_dump('CAS N°4');
            }
            ?>
        </section>
    </main>

    <footer class="footer">
        <?= fetchFooter() ?>
    </footer>
</body>

<script type="module" src="js/script.js"></script>
<script type="module" src="js/burger.js"></script>
<script type="module" src="js/dropdown-menu.js"></script>
<script type="module" src="js/cards.js"></script>
<script type="module" src="js/vignette.js"></script>

<script>
    // Récupération des données PHP
    var chartData = <?php echo $jsonChartData; ?>;
    var chartColors = <?php echo $jsonChartColors; ?>;
</script>
<script type="module" src="js/dashboard-charts.js"></script>

</html>