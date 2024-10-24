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

if (isset($_SESSION['client']) && $_SESSION['client'] === 1 && isset($_SESSION['boss']) && $_SESSION['boss'] === 0) {
    addError('authorization_ko');
    redirectTo('dashboard.php');
    exit;
}

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

$targetAnnualSpendings = [];
$brandsAnnualSpendings = [];

if (
    isset($_SESSION['filter']) && isset($_SESSION['filter']['id_company']) && isset($_SESSION['filter']['year'])
    || isset($_SESSION['client']) && $_SESSION['client'] === 1 && isset($_SESSION['filter']['year'])
) {
    // Récupérer les dépenses annuelles par objectif
    $targetAnnualSpendings = getAnnualSpendingsByTargetHistory($dbCo, $_SESSION);
    $brandsAnnualSpendings = getAnnualSpendingByBrandHistory($dbCo, $_SESSION);

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
    if (!empty($targetChartColors)) {
        $jsonTargetChartColors = json_encode($targetChartColors);
    }

    $brandChartData = [];
    $brandChartColors = [];

    foreach ($brandsAnnualSpendings as $brandData) {
        $brandName = $brandData['brand_name'];
        $totalSpent = $brandData['total_spent'];
        $brandHex = $brandData['legend_colour_hex'];

        // Ajouter les données pour chaque marque
        $brandChartData[] = [$brandName, $totalSpent];

        // Associer la couleur hexadécimale de la marque
        $brandChartColors[$brandName] = $brandHex;
    }

    // Convertir les données en JSON pour les transmettre à JavaScript
    $jsonBrandChartData = json_encode($brandChartData);
    if (!empty($brandChartColors)) {
        $jsonBrandChartColors = json_encode($brandChartColors);
    }
}
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
        <?= fetchNav($_SESSION, $companies, '', '', '', '', 'nav__itm--active') ?>
    </nav>

    <main class="container container--campaigns container__flex">
        <div class="notifs">
            <?php
            echo getErrorMessage($errors);
            echo getSuccessMessage($messages);
            ?>
        </div>

        <h2 class="ttl lineUp">Historique</h2>

        <div class="card">
            <form class="card__section" action="actions-filter.php" method="post" id="filter-form">
                <ul class="form__lst form__lst--row">
                    <div class="form__lst--flex">
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
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                    <input type="hidden" name="action" value="filter-history">
            </form>
            <form action="actions-filter.php" method="post" id="reinit-form">
                <input type="submit" class="button button--reinit" id="filter-reinit" aria-label="Réinitialise tous les filtres" value="" title="Réinitialiser les filtres">
                <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                <input type="hidden" name="action" value="filter-reinit">
            </form>
        </div>

        <?php
        if (isset($_SESSION['filter']['year']) && $_SESSION['client'] === 0) {
            echo '
            <div class="card">
            <section class="card__section card__section--row" aria-labelledby="filter-ttl">
            <h3 id="filter-ttl">Filtres appliqués :</h3>';
            if (isset($_SESSION['filter']) && isset($_SESSION['filter']['year']) && isset($_SESSION['client']) && $_SESSION['client'] === 0) {
                echo '<p class="filter__text">' . $_SESSION['filter']['year'] . '</p>';
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
                                    Budget annuel<br>attribué en ' . $_SESSION['filter']['year'] . '
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
                                    Budget annuel<br>attribué en ' . $_SESSION['filter']['year'] . '
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

        <?php
        if (isset($_SESSION['filter']) && isset($_SESSION['filter']['id_company']) || isset($_SESSION['client']) && $_SESSION['client'] === 1 && $_SESSION['boss'] === 1 && isset($_SESSION['filter']['year'])) {
            // DISPLAY TABLE & DONUT CHART FOR OBJECTIVES
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


            // DISPLAY TABLE & DONUT CHART FOR BRANDS
            echo
            '<div class="card card--grid card--reverse">
        <div class="card">
            <h2 class="ttl lineUp">Répartition du budget annuel<br> dépensé par marque</h2>
            <!-- GRAPHIQUES DONUT  -->
            <section class="card__section">
                <div id="chart-brand"></div>
            </section>
        </div>
        <div class="card">
            <h2 class="ttl lineUp">Budget annuel attribué<br> par marque</h2>
            <!-- TABLEAU DES DÉPENSES PAR MARQUE -->
            <section class="card__section">'
                . generateTableFromDatas($brandsAnnualSpendings) .
                '</section>
        </div>
    </div>';
        }
        ?>

        <section class="card campaign">
            <?= getMessageIfNoCampaign($pastYearsCampaigns, 'dans votre historique ') ?>
            <?php
            if (isset($_SESSION['client']) && $_SESSION['client'] === 1 && $_SESSION['boss'] === 1 && isset($_SESSION['filter']['year'])) {
                echo getCampaignTemplate($dbCo, $history, $_SESSION);
                getMessageIfNoHistory($history, $_SESSION);
                // var_dump('CAS N°1');
            } else if (isset($_SESSION['filter']['id_company'])) {
                // $pastYearsCampaigns = getCompanyCampaignsPastYears($dbCo, $_SESSION, $campaigns);
                echo getCampaignTemplate($dbCo, $history, $_SESSION);
                getMessageIfNoHistory($history, $_SESSION);
                // var_dump('CAS N°2');
            } else if (isset($_SESSION['client']) && $_SESSION['client'] === 0) {
                echo getHistoryCampaignTemplateByCompany($dbCo, $pastYearsCampaigns, $_SESSION, $companies);
                // var_dump('CAS N°3');
            } else {
                // Cas où l'utilisateur est un client
                echo getHistoryCampaignTemplateClient($dbCo, $pastYearsCampaigns, $_SESSION);
                // var_dump('CAS N°4');
            }
            // Si le filtre 'id_company' est défini, mais que c'est une session différente du client

            // Cas général (pas de client et pas de filtre de company. Prend en compte le filtre 'year' si il est en place)
            ?>
        </section>
    </main>

    <a class="button--up" href="#" aria-label="Renvoie en haut de la page." id="scrollTop">
        <img src="img/arrow-up.svg" alt="Flèche vers le haut">
    </a>

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
<!-- Script pour le graphique donut des dépenses par objectif. -->
<script type="module">
    // Récupérer les données PHP encodées en JSON
    var targetChartData = <?php echo $jsonTargetChartData ?? '[]'; ?>;
    var targetChartColors = <?php echo $jsonTargetChartColors ?? '{}'; ?>;

    // Vérifier si des données sont disponibles pour générer le graphique
    if (targetChartData.length === 0) {
        var width = window.innerWidth < 768 ? 495 : 495;
        var height = window.innerWidth < 768 ? 330 : 330;

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
                width: width,
                height: height
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

<!-- Script pour le graphique donut des dépenses par marque. -->
<script type="module">
    // Récupérer les données PHP encodées en JSON
    var brandChartData = <?php echo $jsonBrandChartData ?? '[]'; ?>;
    var brandChartColors = <?php echo $jsonBrandChartColors ?? '{}'; ?>;

    // Vérifier si des données sont disponibles pour générer le graphique
    if (brandChartData.length === 0) {
        var width = window.innerWidth < 768 ? 495 : 495;
        var height = window.innerWidth < 768 ? 330 : 330;

        // Si aucune donnée, on affiche un donut grisé
        var chart = c3.generate({
            bindto: '#chart-brand',
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
            bindto: '#chart-brand',
            data: {
                columns: brandChartData,
                type: 'donut',
                colors: brandChartColors // Appliquer les couleurs des objectifs
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
                title: ""
            }
        });
    }
</script>

</html>