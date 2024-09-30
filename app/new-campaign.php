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
            <h2 class="ttl lineUp" id="new-campaign-ttl"><?php
                                                            if (!isset($_GET['myc']) || !intval($_GET['myc'])) {
                                                                echo 'Nouvelle Campagne';
                                                            } else if (isset($_GET['myc']) || intval($_GET['myc'])) {
                                                                echo 'Modifier la Campagne';
                                                            }
                                                            ?></h2>


            <section class="card__section" aria-labelledby="new-campaign-ttl">
                <form class="form" action="actions.php" method="post" aria-label="Formulaire de création d'une nouvelle campagne">
                    <ul class="form__lst form__lst--app">
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="campaign_name">Nom de la campagne</label>
                            <input class="form__input" type="text" name="campaign_name" id="campaign_name" placeholder="Soldes d'Hiver" required aria-label="Saississez le nom de la nouvelle campagne" value="<?php if (isset($_GET['myc']) && intval($_GET['myc'])) {
                                                                                                                                                                                                                    echo  $selectedCampaign['campaign_name'];
                                                                                                                                                                                                                }  ?>">
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="campaign_target">Objectif de la campagne</label>
                            <select class="form__input form__input--select" type="text" name="campaign_target" id="campaign_target" required aria-label="Sélectionner l'objectif de la campagne de communication">
                                <option class="form__input__placeholder" value="">- Sélectionnez un objectif -</option>
                                <option value="1">Faire connaître</option>
                                <option value="2">Faire aimer</option>
                                <option value="3">Faire agir</option>
                            </select>
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="campaign_company" aria-label="Sélectionner l'entreprise concernée">Entreprise</label>
                            <select class="form__input form__input--select" type="text" name="campaign_company" id="campaign_company" required aria-label="Sélectionner l'entreprise lançant une nouvelle campagne">
                                <?= getDatasAsHTMLOptions($companies, 'Sélectionner une entreprise', 'id_company', 'company_name'); ?>
                            </select>
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="campaign_interlocutor">Interlocuteur</label>
                            <select class="form__input form__input--select" type="text" name="campaign_interlocutor" id="campaign_interlocutor" required aria-label="Sélectionner l'interlocuteur au sein de l'entreprise">
                                <!-- Les options sont automatiquement générées en javascript quand l'entreprise est sélectionnée. -->
                            </select>
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="user_TDC">Chargé de la campagne</label>
                            <select class="form__input form__input--select" type="text" name="user_TDC" id="user_TDC" required aria-label="Sélectionner le chargé de la campagne dans votre entreprise">
                                <?= getDatasAsHTMLOptions($nonClientUsers, 'Sélectionner un collaborateur', 'id_user', 'firstname', 'lastname') ?>
                            </select>
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="date">Date de la campagne</label>
                            <input class="form__input form__input--date" type="date" name="date" id="date" required aria-label="Sélectionner la date de l'opération" value="<?php
                                                                                                                                                                            if (isset($_GET['myc']) && intval($_GET['myc'])) {
                                                                                                                                                                                echo $selectedCampaign['date'];
                                                                                                                                                                            }
                                                                                                                                                                            ?>">
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="budget">Budget investi (sans €)</label>
                            <input class="form__input form__input--number" type="text" name="budget" id="budget" placeholder="12500" required aria-label="Saississez le budget de la nouvelle campagne ou 0 si il n'a pas encore été défini." value="<?php
                                                                                                                                                                                                                                                        if (isset($_GET['myc']) && intval($_GET['myc'])) {
                                                                                                                                                                                                                                                            echo $selectedCampaign['budget'];
                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                        ?>">
                        </li>
                        <?php
                        if (isset($_GET['myc']) && intval($_GET['myc'])) {
                            echo '<input class="button button--new-campaign" type="submit" value="Modifier campagne" aria-label="Valider la création de la nouvelle campagne">';
                            echo '<input type="hidden" name="action" value="modify-campaign">';
                            echo '<input type="hidden" name="id_campaign" value="' . $selectedCampaign['id_campaign'] . '">';
                        } else {
                            echo '<input class="button button--new-campaign" type="submit" value="Créer la campagne" aria-label="Valider la création de la nouvelle campagne">';
                            echo '<input type="hidden" name="action" value="create-campaign">';
                        }
                        ?>
                        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
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