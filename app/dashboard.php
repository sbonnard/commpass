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

if (isset($_SESSION['filter']) && isset($_SESSION['filter']['id_company']) || isset($_SESSION['client']) && $_SESSION['client'] === 1) {
    // Récupérer les dépenses annuelles par objectif
    $targetAnnualSpendings = getAnnualSpendingsByTarget($dbCo, $_SESSION);

    // Préparer les données et les couleurs pour le graphique
    $targetChartData = [];
    $targetchartColors = [];

    foreach ($targetAnnualSpendings as $targetData) {
        $targetName = $targetData['target'];
        $totalSpent = $targetData['total'];
        $targetHex = $targetData['target_legend_hex'];

        // Ajouter les données pour chaque objectif
        $targetChartData[] = [$targetName, $totalSpent];

        // Associer la couleur hexadécimale de l'objectif
        $targetChartColors[$targetName] = $targetHex;
    }

    // Convertir les données en JSON pour les transmettre à JavaScript
    $jsonTargetChartData = json_encode($targetChartData);
    if(!empty($targetChartColors)) {
        $jsonTargetChartColors = json_encode($targetChartColors);
    }
}

// var_dump($campaigns);
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
        <?= fetchNav($_SESSION, 'nav__itm--active') ?>
    </nav>

    <main class="container container--campaigns container__flex">
        <div class="notifs">
            <?php
            echo getErrorMessage($errors);
            echo getSuccessMessage($messages);
            ?>
        </div>

        <h2 class="ttl lineUp">
            Bonjour <?= $user['firstname'] ?><br>
            <span class="ttl--tertiary"><?= getCompanyName($dbCo, $_SESSION) ?></span>
        </h2>

        <div class="button__section">
            <?php
            if (isset($_SESSION['client']) && $_SESSION['client'] === 0) {
                echo '<a href="new-campaign.php" class="button button--new-campaign" aria-label="Redirige vers un formulaire de création de campagne de com">Nouvelle campagne</a>';
            }
            ?>
        </div>

        <?php
        if (isset($_SESSION['client']) && $_SESSION['client'] === 0) {
            echo
            '<div class="card">
                <form class="card__section" action="actions.php" method="post" id="filter-form">
                    <ul class="form__lst form__lst--app">
                        <div class="form__lst--flex">
                            <li class="form__itm">
                                <label for="client-filter">Sélectionner un client</label>
                                <select class="form__input form__input--select" type="date" name="client-filter" id="client-filter" required>
                                    ' . getCompaniesAsHTMLOptions($companies) . '
                                </select>
                            </li>
                            <li class="form__itm">
                                <label for="target-filter">Objectifs de la campagne (optionnel)</label>
                                <select class="form__input form__input--select" type="date" name="target-filter" id="target-filter">
                                    ' . getTargetsAsHTMLOptions($communicationObjectives) . '
                                </select>
                            </li>
                        </div>
                        <input type="submit" class="button button--filter" id="filter-button" aria-label="Filtrer les données entrées" value="Filtrer">
                        <input type="hidden" name="token" value="' . $_SESSION['token'] . '">
                        <input type="hidden" name="action" value="filter-campaigns">
                </form>
                <form action="actions.php" method="post" id="reinit-form">
                    <input type="submit" class="button button--reinit" id="filter-reinit" aria-label="Réinitialise tous les filtres" value="" title="Réinitialiser les filtres">
                    <input type="hidden" name="token" value="' . $_SESSION['token'] . '">
                    <input type="hidden" name="action" value="filter-reinit">
                </form>
            </div>';
        }
        ?>

        <h2 class="ttl lineUp">Tableau de bord
            <?php if (isset($_SESSION['filter']) && isset($_SESSION['filter']['id_company'])) {
                echo '<br><span class="ttl--tertiary">' . getClientName($dbCo, $_SESSION) . '</span>';
            }
            ?>
        </h2>

        <?php
        // var_dump($companyAnnualRemainings);
        if (isset($_SESSION['filter']) && isset($_SESSION['filter']['id_company']) || $_SESSION['client'] === 1 && $_SESSION['boss'] === 1) {
            echo
            '<div class="card">
                <section class="card__section">
                    <h3 class="ttl ttl--budget">Budgets de ' . $currentYear . '</h3>
                    <div class="vignettes-section vignettes-section--row">
                        <div class="vignette vignette--bigger vignette--primary">
                            <div class="flex-row">
                                <h4 class="vignette__ttl vignette__ttl--big">
                                    Budget annuel
                                </h4>'
                . displayButtonIfNotClient($_SESSION) .
                '</div>
                            <p class="vignette__price vignette__price--big">' . formatPrice(fetchCompanyAnnualBudget($dbCo, $_SESSION, $_GET), "€") . '</p>
                        </div>
                        <div class="vignette vignette--bigger vignette--secondary">
                            <h4 class="vignette__ttl vignette__ttl--big">
                                Budget dépensé
                            </h4>
                            <p class="vignette__price vignette__price--big">' . formatPrice(calculateAnnualSpentBudget($dbCo, $_SESSION, $_GET), '€') . '</p>
                        </div>
                        <div class="vignette vignette--bigger vignette--tertiary ' . turnVignetteRedIfNegative($companyAnnualRemainings) . '" data-vignette="">
                            <h4 class="vignette__ttl vignette__ttl--big">
                                Budget restant
                            </h4>
                            <p class="vignette__price vignette__price--big">' . formatPrice(calculateAnnualRemainingBudget($dbCo, $_SESSION), '€') . '</p>
                        </div>
                    </div>
                </section>
            </div>';
        }
        ?>

        <?php
        if (isset($_SESSION['filter']) && isset($_SESSION['filter']['id_company']) || isset($_SESSION['client']) && $_SESSION['client'] === 1 && $_SESSION['boss'] === 1) {
            echo
            '<div class="card card--grid">
        <div class="card">
            <h2 class="ttl lineUp">Répartition du budget annuel<br> dépensé par objectif</h2>
            <!-- GRAPHIQUES DONUT  -->
            <section class="card__section">
                <div id="chart-target"></div>
            </section>
        </div>
        <div class="card">
            <h2 class="ttl lineUp">Budget annuel attribué<br> par objectif</h2>
            <!-- TABLEAU DES DÉPENSES PAR OBJECTIF -->
            <section class="card__section">'
                . generateTableFromTargetDatas($targetAnnualSpendings) .
                '</section>
        </div>
    </div>';
        }
        ?>
        <h2 class="ttl">Mes campagnes <?= $currentYear ?></h2>
        <section class="card campaign">
            <?= getMessageIfNoCampaign($currentYearCampaigns) ?>
            <?php
            if (!isset($_SESSION['filter']) && !isset($_SESSION['filter']['id_company'])) {
                echo getCampaignTemplate($dbCo, $currentYearCampaigns, $_SESSION);
            } else if (isset($_SESSION['filter']) && isset($_SESSION['filter']['id_company']) && $_SESSION['client'] === 0) {
                $currentYearCampaigns = getCompanyFilteredCampaigns($dbCo, $_SESSION);
                echo getCampaignTemplate($dbCo, $currentYearCampaigns, $_SESSION);
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

<!-- Script pour les multiple graphiques de campagne. -->
<script>
    // Récupération des données PHP
    var chartData = <?php echo $jsonChartData; ?>;
    var chartColors = <?php echo $jsonChartColors; ?>;
</script>
<script type="module" src="js/dashboard-charts.js"></script>

<!-- Script pour le graphique donut des dépenses par objectif. -->
<script type="module">
    // Récupérer les données PHP encodées en JSON
    var targetChartData = <?php echo $jsonTargetChartData ?? '[]'; ?>;
    var targetChartColors = <?php echo $jsonTargetChartColors ?? '{}'; ?>;

    // Vérifier si des données sont disponibles pour générer le graphique
    if (targetChartData.length === 0) {
        var width = window.innerWidth < 768 ? 375 : 450;
        var height = window.innerWidth < 768 ? 250 : 300;

        // Si aucune donnée, on affiche un donut grisé
        var chart = c3.generate({
            bindto: '#chart-target',
            data: {
                columns: [
                    ['En attente d\'opération', 1] // Donut "Aucune donnée"
                ],
                type: 'donut',
                colors: {
                    'En attente d\'opération': '#d3d3d3' // Couleur grise pour "Aucune donnée"
                }
            },
            size: {
                width: width,
                height: height
            },
            padding: {
                right: 20,
                left: 20
            },
            donut: {
                title: "Aucune opération"
            }
        });
    } else {
        // Générer le graphique avec les données et couleurs
        var chart = c3.generate({
            bindto: '#chart-target',
            data: {
                columns: targetChartData,
                type: 'donut',
                colors: targetChartColors // Appliquer les couleurs des objectifs
            },
            size: {
                width: window.innerWidth < 768 ? 375 : 450,
                height: window.innerWidth < 768 ? 250 : 300
            },
            padding: {
                right: 20,
                left: 20
            },
            donut: {
                title: ""
            }
        });
    }
</script>

</html>