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

checkUserClientStatus($_SESSION);

if (
    isset($_GET['client']) && $_GET['client'] == $_SESSION['id_company']
    || !intval($_GET['client'])
    || !is_array(getAllCompanyDatas($dbCo, $_GET)) || getAllCompanyDatas($dbCo, $_GET) == false
) {
    redirectTo('errors/403.php');
    exit;
}

// unsetFilters($_SESSION);

// Récupérer les données d'une entreprise. 

$selectedCompany = getAllCompanyDatas($dbCo, $_GET);

// Le filtre permet de récupérer les données d'une entreprise en session.
$_SESSION['filter']['id_company'] = $selectedCompany['id_company'];

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
    $brandsAnnualSpendings = getAnnualSpendingByBrand($dbCo, $_SESSION);

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

if (isset($_SESSION['filter']) && isset($_SESSION['filter']['id_company'])) {
    // Récupérer les dépenses annuelles par partenaire
    $partnerAnnualSpendings = getAnnualBudgetPerPartnerPerCompany($dbCo, $_SESSION, $_GET);

    // Préparer les données et les couleurs pour le graphique
    $partnerChartData = [];
    $partnerChartColors = [];

    foreach ($partnerAnnualSpendings as $partnerData) {
        $partnerName = $partnerData['partner_name'];
        $totalSpent = $partnerData['annual_spendings'];
        $partnerHex = $partnerData['partner_colour'];

        // Ajouter les données pour chaque partenaire
        $partnerChartData[] = [$partnerName, $totalSpent];

        // Associer la couleur hexadécimale du partenaire
        $partnerChartColors[$partnerName] = $partnerHex;
    }

    // Convertir les données en JSON pour les transmettre à JavaScript
    $jsonPartnerChartData = json_encode($partnerChartData);
    $jsonPartnerChartColors = !empty($partnerChartColors) ? json_encode($partnerChartColors) : '';
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?= fetchHead('Commpass'); ?>
</head>

<body>

    <header class="header">
        <?= fetchHeader('dashboard.php', 'Mon tableau de bord') ?>
    </header>

    <nav class="nav hamburger__menu" id="menu" aria-label="Navigation principale du site">
        <?= fetchNav($_SESSION, $companies, '', '', 'nav__itm--active') ?>
    </nav>

    <main class="container container--campaigns container__flex">
        <div class="notifs">
            <?php
            echo getErrorMessage($errors);
            echo getSuccessMessage($messages);
            ?>
        </div>

        <div class="flex-row">
            <img class="client__logo" src="<?= $selectedCompany['logo_url'] ?>" alt="">
            <h2 class="ttl lineUp">Tableau de bord
                <br><span class="ttl--tertiary"><?= $selectedCompany['company_name'] ?></span>
            </h2>
        </div>

        <div class="button__section">
            <a href="/new-campaign.php?client=<?= $selectedCompany['id_company'] ?>" class="button button--new-campaign" aria-label="Redirige vers un formulaire de création de campagne de com">Nouvelle campagne</a>
        </div>

        <div class="button__section">
            <ul class="button__section space-between" data-client-menu="" aria-label="Options multiples d'ajout d'interlocuteur ou de marque">
                <li class="history-lnk"><a class="nav__lnk nav__lnk--new-campaign" href="#client-campaigns" aria-label="Vous amène directement aux campagnes clients">Campagnes ▼</a></li>
                <li class="history-lnk"><a class="nav__lnk nav__lnk--history" href="history.php?client=<?= $selectedCompany['id_company'] ?>" aria-label="Consulter l'historique de <?= $selectedCompany['company_name'] ?>">Historique</a></li>
                <li class="history-lnk"><a class="nav__lnk nav__lnk--user-plus" href="new-user.php?client=<?= $_SESSION['filter']['id_company'] ?>" aria-label="Lien vers un formulaire de création d'interlocuteur pour l'entreprise <?= $selectedCompany['company_name'] ?>">Créer interlocuteur</a></li>
                <li class="history-lnk"><a class="nav__lnk nav__lnk--new-brand" href="new-brand.php?client=<?= $_SESSION['filter']['id_company'] ?>" aria-label="Lien vers un formulaire de création de marque pour l'entreprise <?= $selectedCompany['company_name'] ?>">Créer marque</a></li>
            </ul>
        </div>

        <?php
        if (isset($_SESSION['filter']) && isset($_SESSION['filter']['id_company']) || $_SESSION['client'] === 1 && $_SESSION['boss'] === 1) {

            if ($companyAnnualBudget === 0) {
                $companyAnnualRemainings = 0;
            }

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
                        <div class="vignette vignette--bigger vignette--tertiary ' . ($companyAnnualBudget > 0 ? turnVignetteRedIfNegative($companyAnnualRemainings) : '') . '" data-vignette="">
                            <h4 class="vignette__ttl vignette__ttl--big">
                                Budget restant
                            </h4>
                            <p class="vignette__price vignette__price--big">' . formatPrice($companyAnnualRemainings, '€') . '</p>
                </div>
                    </div>
                </section>
            </div>';
        }
        ?>

        <?php
        if (isset($_SESSION['filter']) && isset($_SESSION['filter']['id_company']) || isset($_SESSION['client']) && $_SESSION['client'] === 1 && $_SESSION['boss'] === 1) {
            // DISPLAY TABLE & DONUT CHART FOR OBJECTIVES
            echo
            '<div class="card card--grid">
        <div class="card">
            <h2 class="ttl lineUp">Répartition annuelle par objectif</h2>
            <!-- GRAPHIQUES DONUT  -->
            <section class="card__section">
                <div id="chart-target"></div>
            </section>
        </div>
        <div class="card">
            <h2 class="ttl lineUp">Budget annuel par objectif</h2>
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
            <h2 class="ttl lineUp">Répartition annuelle par marque</h2>
            <!-- GRAPHIQUES DONUT  -->
            <section class="card__section">
                <div id="chart-brand"></div>
            </section>
        </div>
        <div class="card">
            <h2 class="ttl lineUp">Budget annuel par marque</h2>
            <!-- TABLEAU DES DÉPENSES PAR MARQUE -->
            <section class="card__section">'
                . generateTableFromDatas($brandsAnnualSpendings) .
                '</section>
        </div>
    </div>';

            // DISPLAY TABLE & DONUT CHART FOR PARTNERS
            echo
            '<div class="card card--grid">
        <div class="card">
            <h2 class="ttl lineUp">Répartition annuelle par partenaire</h2>
            <!-- GRAPHIQUES DONUT  -->
            <section class="card__section">
                <div id="chart-partner"></div>
            </section>
        </div>
        <div class="card">
            <h2 class="ttl lineUp">Budget annuel par partenaire</h2>
            <!-- TABLEAU DES DÉPENSES PAR PARTENAIRE -->
            <section class="card__section">'
                . generatePartnerTable($partnerAnnualSpendingsClient) .
                '</section>
        </div>
    </div>';
        }
        ?>

        <h2 class="ttl lineUp" id="client-campaigns">Les campagnes <?= $currentYear ?></h2>
        <!-- USELESS FILTERS  -->
        <!-- <div class="card">
            <form class="card__section" action="actions-filter.php" method="post" id="filter-form" aria-label="formulaire de filtre">
                <ul class="form__lst form__lst--row">
                    <div class="form__lst--flex">
                        <li class="form__itm">
                            <label for="target-filter">Objectifs de la campagne (optionnel)</label>
                            <select class="form__input form__input--select" type="date" name="target-filter" id="target-filter">
                                <?= getTargetsAsHTMLOptions($communicationObjectives) ?>
                            </select>
                        </li>
                    </div>
                    <input type="submit" class="button button--filter" id="filter-button" aria-label="Filtrer les données entrées" value="Filtrer">
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                    <input type="hidden" name="action" value="filter-campaigns">
            </form>
            <form action="actions-filter.php" method="post" id="reinit-form">
                <input type="submit" class="button button--reinit" id="filter-reinit" aria-label="Réinitialise tous les filtres" value="" title="Réinitialiser les filtres">
                <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                <input type="hidden" name="action" value="filter-reinit">
            </form>
        </div> -->
        <section class="card <?php
                                if (!empty($companyCurrentYearCampaigns) || $_SESSION['client'] === 0) {
                                    echo 'campaign';
                                }
                                ?>">
            <?php

            if (!isset($_SESSION['filter']) && $_SESSION['client'] === 0) {
                // CAS ACCESSIBLE UNIQUEMENT POUR UN PROFIL NON-CLIENT
                echo getCampaignTemplateByCompany($dbCo, $currentYearCampaigns, $_SESSION, $companies);
                echo getMessageIfNoCampaign($currentYearCampaigns);
                // var_dump('Cas 1');
            } else if (isset($_SESSION['filter']) && isset($_SESSION['filter']['id_company']) && $_SESSION['client'] === 0) {
                // CAS ACCESSIBLE UNIQUEMENT POUR UN PROFIL NON-CLIENT
                $currentYearCampaigns = getCompanyFilteredCampaigns($dbCo, $_SESSION);
                echo getCampaignTemplate($dbCo, $currentYearCampaigns, $_SESSION);
                echo getMessageIfNoCampaign($currentYearCampaigns);
                // var_dump('Cas 2');
            } else if ($_SESSION['client'] === 1 && $_SESSION['boss'] === 1) {
                // CAS ACCESSIBLE POUR UN PROFIL CLIENT ET GÉRANT
                $currentYearCampaigns = getCompanyFilteredCampaigns($dbCo, $_SESSION);
                echo getMessageIfNoCampaign($currentYearCampaigns);
                echo getCampaignTemplate($dbCo, $currentYearCampaigns, $_SESSION);
                // var_dump('Cas 3');
            } else if ($_SESSION['client'] === 1 && $_SESSION['boss'] === 0) {
                // CAS ACCESSIBLE POUR UN PROFIL CLIENT ET EMPLOYÉ
                echo getMessageIfNoCampaign($currentYearCampaigns);
                echo getCampaignTemplate($dbCo, $currentYearCampaigns, $_SESSION);
                // var_dump('Cas 4');
            } else {
                echo 'Aucun bloc atteint';
            }
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
<?php
// LE SCRIPT DE DROPDOWN N'EST UTILE QUE POUR LES UTILISATEURS NON-CLIENTS
if (isset($_SESSION['client']) && $_SESSION['client'] === 0) {
    echo '<script type="module" src="js/dropdown-menu.js"></script>';
}
?>
<script type="module" src="js/cards.js"></script>
<script type="module" src="js/vignette.js"></script>
<script type="module" src="js/client-menu.js"></script>

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