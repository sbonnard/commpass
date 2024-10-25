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
require_once "includes/classes/class.operation.php";
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
    header('Location: dashboard');
    exit;
}

checkUserClientStatus($_SESSION);

if (!isset($_GET['myo'])) {
    $operation = [
        "description" => "",
        "price" => "",
        "operation_date" => ""
    ];
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
        <?= fetchNav($_SESSION, $companies) ?>
    </nav>

    <main class="container container--campaigns container__flex">
        <div class="notifs">
            <?php
            echo getErrorMessage($errors);
            echo getSuccessMessage($messages);
            ?>
        </div>

        <div class="card">
            <h2 class="ttl lineUp" id="new-operation-ttl">
                <?php
                if (isset($_GET['myo']) && intval($_GET['myo'])) {
                    echo 'Modifier l\'opération<br>';
                } else if (!isset($_GET['myo']) && isset($_GET['myc']) && intval($_GET['myc'])) {
                    echo
                    'Nouvelle Opération<br>
                    pour la campagne<br>'
                        . $selectedCampaign['campaign_name'] . ' de <br>';
                }
                ?>
                <span class="ttl--tertiary"><?= $selectedCampaign['company_name'] ?></span>
            </h2>

            <section class="card__section" aria-labelledby="new-operation-ttl">
                <form class="form" action="actions.php" method="post" aria-label="Formulaire de création d'une nouvelle opération">
                    <ul class="form__lst form__lst--app">
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="operation_description">Description de l'opération</label>
                            <textarea class="form__input form__textarea" type="" name="operation_description" id="operation_description" placeholder="Flocage d'un véhicule..." required aria-label="Saississez la description de l'opération." autofocus><?php
                                                                                                                                                                                                                                                            echo $operation['description'];
                                                                                                                                                                                                                                                            if (isset($_SESSION['form_data']['description'])) {
                                                                                                                                                                                                                                                                echo $_SESSION['form_data']['description'];
                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                            ?></textarea>
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="operation_amount">Prix de l'opération (sans €)</label>
                            <input class="form__input form__input--number" type="text" name="operation_amount" id="operation_amount" placeholder="12500" required value="<?php echo $operation['price'];
                                                                                                                                                                            if (isset($_SESSION['form_data']['price']) && $_SESSION['form_data']['price'] >= 0) {
                                                                                                                                                                                echo $_SESSION['form_data']['price'];
                                                                                                                                                                            }
                                                                                                                                                                            ?>" aria-label="Fixe le montant d'une opération.">
                        </li>

                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="operation_brand">Marque(s)</label>
                            <select class="form__input form__input--select" type="text" name="operation_brand" id="operation_brand" required aria-label="Sélectionner la marque concernée">
                                <option value="">- Sélectionner une marque -</option>
                                <option value="0">Toutes les marques</option>
                                <?= getCompanyBrandsAsHTMLOptions(getCompanyBrands($dbCo, $selectedCampaign)); ?>
                            </select>
                        </li>

                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="operation_media" aria-label="Sélectionner le media utilisé">Média</label>
                            <select class="form__input form__input--select" type="text" name="operation_media" id="operation_media" required aria-label="Sélectionner le media de l'opération.">
                                <?= getMediaAsHTMLOptions($media, $operation) ?>
                            </select>
                            <button class="create-lnk" style="text-align:left;" id="media-lnk">+ Créer un média</button>
                        </li>

                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="operation_partner">Partenaire (optionnel)</label>
                            <select class="form__input form__input--select" type="text" name="operation_partner" id="operation_partner" aria-label="Sélectionner un partenaire de l'opération s'il y en a un.">
                                <?= getPartnersAsHTMLOptions($partners) ?>
                            </select>
                            <button type="button" class="create-lnk" style="text-align:left;" id="partner-lnk">+ Créer un partenaire</ submit="false">
                        </li>

                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="date">Date de l'opération</label>
                            <input class="form__input form__input--date" type="date" name="date" id="date" required aria-label="Sélectionner la date de l'opération" value="<?php echo $operation['operation_date'];
                                                                                                                                                                            if (isset($_SESSION['form_data']['date'])) {
                                                                                                                                                                                echo $_SESSION['form_data']['date'];
                                                                                                                                                                            }
                                                                                                                                                                            ?>">
                        </li>

                        <?php
                        if (isset($_GET['myc']) && !isset($_GET['myo'])) {
                            echo '
                            <input class="button button--add" type="submit" value="Ajouter opération" aria-label="Valider l\'ajout de l\'opération">
                            <input type="hidden" name="action" value="create-operation">';
                        } else if (isset($_GET['myc']) && isset($_GET['myo'])) {
                            echo '
                            <input class="button button--confirm" type="submit" value="Éditer l\'opération" aria-label="Valider la création de la nouvelle campagne">
                            <input type="hidden" name="action" value="edit-operation">';
                        }
                        ?>
                        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                        <input type="hidden" name="id_campaign" value="<?= $selectedCampaign['id_campaign'] ?>">
                        <input type="hidden" name="id_company" value="<?= $selectedCampaign['id_company'] ?>">
                        <?php if (isset($_GET['myo']) && isset($_GET['myc'])) {
                            echo '<input type="hidden" name="id_operation" value="' . $operation['id_operation'] . '">';
                        }
                        ?>
                    </ul>
                </form>
            </section>

            <!-- Formulaire de création d'un nouveau média si absent de la liste dans le select.  -->
            <form class="form hidden" action="api" method="post" id="new-media-form" aria-label="Création d'un nouveau média si absent de la liste précédente.">
                <ul class="form__lst">
                    <li class="form__itm form__itm--small">
                        <label for="add-media" class="text-small">Créer un média</label>
                        <input class="form__input form__input--small" type="text" name="add-media" id="add-media" placeholder="Nouveau média">
                        <input class="button--plus form__plus" type="submit" value="">
                    </li>
                </ul>
                <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                <input type="hidden" name="action" value="add-media">
            </form>

            <!-- Formulaire de création d'un nouveau partenaire si absent de la liste dans le select.  -->
            <form class="form hidden" action="api" method="post" id="new-partner-form" aria-label="Création d'un nouveau partenaire si absent de la liste précédente.">
                <ul class="form__lst">
                    <li class="form__itm form__itm--small">
                        <label for="add-partner" class="text-small">Créer un partenaire</label>
                        <input class="form__input form__input--small" type="text" name="add-partner" id="add-partner" placeholder="Nouveau partenaire">
                        <input class="button--plus form__plus" type="submit" value="">
                    </li>
                </ul>
                <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                <input type="hidden" name="action" value="add-partner">
            </form>
        </div>


    </main>

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
<script type="module" src="js/ajax-new-media-partner.js"></script>

<?php
if (!isset($_SESSION['filter']['id_company'])) {
?>
    <script type="module" src="js/ajax.js"></script>
<?php } ?>

</html>