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

unsetFilters($_SESSION);

// $jsonPartnerChartData = json_encode($partnerChartData);
// $jsonPartnerChartColors = json_encode($partnerChartColors);

// Récupérer les dépenses annuelles par partenaire
$partnerAnnualSpendings = getAnnualBudgetPerPartner($dbCo);

// Préparer les données et les couleurs pour le graphique
$partnerChartData = [];
$partnerchartColors = [];

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
if (!empty($partnerChartColors)) {
    $jsonPartnerChartColors = json_encode($partnerChartColors);
}

$partnerChartData = [];
$partnerChartColors = [];

foreach ($partnerAnnualSpendings as $partnerData) {
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
        echo fetchHeader('dashboard', 'Mon tableau de bord');
        ?>
    </header>

    <nav class="nav hamburger__menu" id="menu" aria-label="Navigation principale du site">
        <?= fetchNav($_SESSION, $companies, '', '', '', 'nav__itm--active') ?>
    </nav>

    <main class="container container--campaigns container__flex">
        <div class="notifs">
            <?php
            echo getErrorMessage($errors);
            echo getSuccessMessage($messages);
            ?>
        </div>

        <h2 class="ttl lineUp" id="partners-ttl">
            Les partenaires de<br>
            <span class="ttl--tertiary"><?= getCompanyName($dbCo, $_SESSION) ?></span>
        </h2>

        <div class="card">
            <section class="card__section" aria-labelledby="partners-ttl">
                <?php
                $partnersDatas = '';

                $partnersDatas .= '<ul>';
                foreach ($partners as $partner) {
                    $partnersDatas .= '<li class="partner medium-text" data-js-partner="partner">' . $partner['partner_name'] . '</li>';
                }
                $partnersDatas .= '</ul>';

                echo $partnersDatas;
                ?>

                <form action="actions" method="post" class="form gradient-border gradient-border--top" aria-label="Formulaire d'ajout d'un nouveau partenaire.">
                    <ul class="form__lst form__lst--app">
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="partner_name">Ajouter un partenaire</label>
                            <input class="form__input" type="text" name="partner_name" id="partner_name" placeholder="Tendance Ouest" required>
                        </li>
                        <input class="button button--partner" type="submit" value="Créer partenaire">
                        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                        <input type="hidden" name="action" value="create_partner">
                    </ul>
                </form>
            </section>
        </div>

        <div class="card card--grid">
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
                <section class="card__section">
                    <?= generatePartnerTable($partnerAnnualSpendings) ?>
                </section>
            </div>
        </div>

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
<script type="module" src="js/ajax-partner.js"></script>

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
                title: "Aucune opération"
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