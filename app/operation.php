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
    header('Location: dashboard.php');
    exit;
}

checkUserClientStatus($_SESSION);

if (!isset($_GET['myo'])) {
    $operation = [
        "description" => "",
        "price" => "",
        "date_" => ""
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
        <?= fetchNav($_SESSION) ?>
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
                            <textarea class="form__input form__textarea" type="" name="operation_description" id="operation_description" placeholder="Flocage d'un véhicule..." required aria-label="Saississez la description de l'opération."><?= $operation['description']; ?></textarea>
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="operation_amount">Prix de l'opération (sans €)</label>
                            <input class="form__input form__input--number" type="text" name="operation_amount" id="operation_amount" placeholder="12500" required value="<?= $operation['price']; ?>" aria-label="Fixe le montant d'une opération.">
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
                                <?= getMediaAsHTMLOptions($media) ?>
                            </select>
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="operation_partner">Partenaire (optionnel)</label>
                            <select class="form__input form__input--select" type="text" name="operation_partner" id="operation_partner" aria-label="Sélectionner un partenaire de l'opération s'il y en a un.">
                                <?= getPartnersAsHTMLOptions($partners) ?>
                            </select>
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="date">Date de l'opération</label>
                            <input class="form__input form__input--date" type="date" name="date" id="date" required aria-label="Sélectionner la date de l'opération" value="<?= $operation['date_']; ?>">
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