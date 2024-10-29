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

if (!isset($_GET['myc'])) {
    header('Location: dashboard.php');
}

if (isset($_GET['client']) && intval($_GET['client'])) {
    $_SESSION['filter']['id_company'] = $_GET['client'];
}

$campaignResults = getSpendingByBrandByCampaign($dbCo, $campaigns, $_GET);
$brandsSpendings = mergeResults($campaignResults);

$chartData = [];
foreach ($brandsSpendings as $row) {
    $chartData[] = [$row['brand_name'], floatval($row['total_spent']), $row['legend_colour_hex']];
}

$jsonData = json_encode($chartData);


// Préparer les données et les couleurs pour le graphique
$partnerChartData = [];
$partnerchartColors = [];

foreach ($partnerCampaignSpendings as $partnerData) {
    $partnerName = $partnerData['partner_name'];
    $totalSpent = $partnerData['annual_spendings'];
    $partnerHex = $partnerData['partner_colour'];

    // Ajouter les données pour chaque partenaire
    $partnerChartData[] = [$partnerName, $totalSpent];

    // Associer la couleur hexadécimale du partenaire
    $partnerChartColors[$partnerName] = $partnerHex;
}

// Convertir les données en JSON pour les transmettre à JavaScript
// $jsonPartnerChartData = json_encode($partnerChartData);
if (!empty($partnerChartColors)) {
    $jsonPartnerChartColors = json_encode($partnerChartColors);
}

$partnerChartData = [];
$partnerChartColors = [];

foreach ($partnerCampaignSpendings as $partnerData) {
    $partnerName = $partnerData['partner_name'];
    $totalSpent = $partnerData['annual_spendings'];
    $partnerHex = $partnerData['partner_colour'];

    // Ajouter les données pour chaque marque
    $partnerChartData[] = [$partnerName, $totalSpent];

    // Associer la couleur hexadécimale de la marque
    $partnerChartColors[$partnerName] = $partnerHex;
}

// Convertir les données en JSON pour les transmettre à JavaScript
$jsonPartnerChartData = json_encode($partnerChartData);
if (!empty($partnerChartColors)) {
    $jsonPartnerChartColors = json_encode($partnerChartColors);
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
        <?= fetchNav($_SESSION, $companies, 'nav__itm--active') ?>
    </nav>

    <main class="container container--campaigns container__flex" id="pdfContent">
        <div class="notifs">
            <?php
            echo getErrorMessage($errors);
            echo getSuccessMessage($messages);
            ?>
        </div>

        <div class="flex-row space-between">
            <div class="flex-column">
                <a href="my-client.php?client=<?= $_SESSION['filter']['id_company'] ?>">
                    <h2 class="ttl lineUp client__ttl"><?= $selectedCampaign['company_name'] ?><br></h2>
                </a>
            </div>
            <div class="flex-row">
                <h2 class="ttl lineUp ttl--tertiary"><?= $selectedCampaign['campaign_name'] ?></h2>
                <?php
                if (isset($_SESSION['client']) && $_SESSION['client'] === 0) {
                    echo
                    '<a class="button--edit" href="new-campaign.php?myc=' . $selectedCampaign['id_campaign'] . '&client=' . $_SESSION['filter']['id_company'] . '" title="éditer la campagne ' . $selectedCampaign['campaign_name'] . '"></a>
                    |' . deleteCampaignButton($selectedCampaign, $_SESSION);
                }
                ?>
            </div>
            <?php
            if (isset($_SESSION['client']) && $_SESSION['client'] === 0) {
                echo '<div class="operation__button"><a href="operation.php?myc=' . $selectedCampaign['id_campaign'] . '" class="button button--add" aria-label="Créer une nouvelle opération">Ajouter opération</a></div>';
            }
            ?>
        </div>

        <div class="card">
            <section class="card__section card__section--row">
                <p class="campaign__interlocutor">Interlocuteur : <?= $selectedCampaign['firstname'] . ' ' . $selectedCampaign['lastname'] ?></p>
                <span>|</span>
                <p class="campaign__interlocutor">Objectif : <?= $selectedCampaign['target_com'] ?></p>
            </section>
        </div>



        <h2 class="ttl lineUp">Données globales</h2>

        <div class="card">
            <section class="card__section card__section--vignettes vignettes-PDF">
                <div class="campaign__stats">
                    <div class="vignettes-section vignettes-section--row">
                        <div class="vignette vignette--bigger vignette--primary">
                            <div class="flex-row">
                                <h4 class="vignette__ttl vignette__ttl--big">
                                    Budget attribué
                                </h4>
                                <?= displayButtonIfNotClient($_SESSION, '?myc=' . $_GET['myc']) ?>
                            </div>
                            <p class="vignette__price vignette__price--big"><?= formatPrice($selectedCampaign['budget'], "€") ?></p>
                        </div>
                        <div class="vignette vignette--secondary vignette--big">
                            <h4 class="vignette__ttl vignette__ttl--big">
                                Budget dépensé
                            </h4>
                            <p class="vignette__price vignette__price--big"><?= calculateSpentBudget($dbCo, $selectedCampaign) ?></p>
                        </div>
                        <div class="vignette vignette--tertiary vignette--big <?= turnVignetteRedIfNegative(calculateRemainingBudget($dbCo, $selectedCampaign)) ?>">
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
                <h2 class="ttl lineUp">Répartition du budget par marque</h2>
                <!-- GRAPHIQUES DONUT  -->
                <section class="card__section">
                    <div id="chart"></div>
                </section>
            </div>
            <div class="card">
                <h2 class="ttl lineUp">Budget par marque</h2>
                <!-- TABLEAU DES DÉPENSES PAR MARQUES -->
                <section class="card__section">
                    <?= generateTableFromDatas($brandsSpendings); ?>
                </section>
            </div>
        </div>

        <div class="card card--grid">
            <div class="card">
                <h2 class="ttl lineUp">Répartition du budget par partenaire</h2>
                <!-- GRAPHIQUES DONUT  -->
                <section class="card__section">
                    <div id="chart-partner"></div>
                </section>
            </div>
            <div class="card">
                <h2 class="ttl lineUp">Budget par partenaire</h2>
                <!-- TABLEAU DES DÉPENSES PAR PARTENAIRE -->
                <section class="card__section">
                    <?= generatePartnerTable($partnerCampaignSpendings) ?>
                </section>
            </div>
        </div>

        <div class="card">
            <h2 class="ttl lineUp">Opérations</h2>
            <!-- OPÉRATIONS DE LA CAMPAGNE DE COMMUNICATION  -->
            <section class="card__section card__section--operations">
                <ul>
                    <?= getCampaignOperationsAsList($campaignOperations, $_SESSION, $selectedCampaign); ?>
                </ul>
            </section>
        </div>
    </main>
    <div class="card">
        <form id="formPDF" action="rapport_pdf" method="post">
            <input type="hidden" id="htmlContent" name="htmlContent" value="">
            <input type="hidden" id="chartImage" name="chartImage" value="">
            <button class="button button--pdf" type="submit" id="generatePDF"></button>
        </form>
    </div>

    <footer class="footer">
        <?= fetchFooter() ?>
    </footer>
</body>

<script type="module" src="js/script.js"></script>
<script type="module" src="js/burger.js"></script>

<?php
// LE SCRIPT DE DROPDOWN N'EST UTILE QUE POUR LES UTILISATEURS NON-CLIENTS
if (isset($_SESSION['client']) && $_SESSION['client'] === 0) {
    echo '<script type="module" src="js/dropdown-menu.js"></script>';
}
?>

<script type="module" src="js/cards.js"></script>
<script type="module" src="js/vignette.js"></script>
<script type="module" src="js/ajax-operation.js"></script>
<script>
    function confirmDelete() {
        return confirm("Êtes-vous sûr de vouloir supprimer cette campagne ?");
    }
</script>
<script>
    // Récupération des données PHP
    var chartData = <?php echo $jsonData; ?>;
</script>
<script type="module" src="js/charts.js"></script>
<script type="module" src="js/pdf-converter.js"></script>

<!-- Script pour le graphique donut des dépenses par partenaire. -->
<script type="module">
    // Récupérer les données PHP encodées en JSON
    var partnerChartData = <?php echo $jsonPartnerChartData ?? '[]'; ?>;
    var partnerChartColors = <?php echo $jsonPartnerChartColors ?? '{}'; ?>;

    // Vérifier si des données sont disponibles pour générer le graphique
    if (partnerChartData.length === 0) {
        var width = window.innerWidth < 768 ? 495 : 495;
        var height = window.innerWidth < 768 ? 330 : 330;

        // Si aucune donnée, on affiche un donut grisé
        var chart = c3.generate({
            bindto: '#chart-partner',
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
                title: "Aucun partenariat"
            }
        });
    } else {
        // Générer le graphique avec les données et couleurs
        var chart = c3.generate({
            bindto: '#chart-partner',
            data: {
                columns: partnerChartData,
                type: 'donut',
                colors: partnerChartColors // Appliquer les couleurs des objectifs
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