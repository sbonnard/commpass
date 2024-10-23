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

if (!isset($_GET['client']) || !intval($_GET['client'])) {
    unset($_SESSION['filter']);
}

var_dump($_SESSION);
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
        <?= fetchNav($_SESSION, $companies, '', 'nav__itm--active') ?>
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

                                                            if (isset($_SESSION['filter']['id_company'])) {
                                                                echo '<br><span class="ttl--tertiary">' . getClientName($dbCo, $_SESSION) . '</span>';
                                                            }
                                                            ?></h2>


            <section class="card__section" aria-labelledby="new-campaign-ttl">
                <form class="form" action="actions-campaign.php" method="post" aria-label="Formulaire de création d'une nouvelle campagne">
                    <ul class="form__lst form__lst--app">
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="campaign_name">Nom de la campagne</label>
                            <input class="form__input" type="text" name="campaign_name" id="campaign_name" placeholder="Soldes d'Hiver" required autofocus aria-label="Saississez le nom de la nouvelle campagne" value="<?php if (isset($_GET['myc']) && intval($_GET['myc'])) {
                                                                                                                                                                                                                                echo  $selectedCampaign['campaign_name'];
                                                                                                                                                                                                                            }

                                                                                                                                                                                                                            if (isset($_SESSION['form_data']) && isset($_SESSION['form_data']['name'])) {
                                                                                                                                                                                                                                echo $_SESSION['form_data']['name'];
                                                                                                                                                                                                                            } ?>">
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="campaign_target">Objectif de la campagne</label>
                            <select class="form__input form__input--select" type="text" name="campaign_target" id="campaign_target" required aria-label="Sélectionner l'objectif de la campagne de communication" value="<?php if (isset($_SESSION['form_data']) && isset($_SESSION['form_data']['id_target'])) {
                                                                                                                                                                                                                                echo $_SESSION['form_data']['id_target'];
                                                                                                                                                                                                                            } ?>">
                                <option class="form__input__placeholder" value="">- Sélectionnez un objectif -</option>
                                <option value="1" <?php if (isset($selectedCampaign['id_target']) && $selectedCampaign['id_target'] === 1) echo 'selected'; ?>>Faire connaître</option>
                                <option value="2" <?php if (isset($selectedCampaign['id_target']) && $selectedCampaign['id_target'] === 2) echo 'selected'; ?>>Faire aimer</option>
                                <option value="3" <?php if (isset($selectedCampaign['id_target']) && $selectedCampaign['id_target'] === 3) echo 'selected'; ?>>Faire agir</option>
                            </select>
                        </li>
                        <?php if (!isset($_SESSION['filter']['id_company'])) { ?>
                            <li class="form__itm form__itm--app">
                                <label class="form__label" for="campaign_company" aria-label="Sélectionner l'entreprise concernée">Entreprise</label>
                                <select class="form__input form__input--select" type="text" name="campaign_company" id="campaign_company" required aria-label="Sélectionner l'entreprise lançant une nouvelle campagne" value="<?php if (isset($_SESSION['form_data']) && isset($_SESSION['form_data']['id_company'])) {
                                                                                                                                                                                                                                    echo $_SESSION['form_data']['id_company'];
                                                                                                                                                                                                                                } ?>">
                                    <!-- Une fois l'entreprise sélectionnée, récupération en AJAX des interlocuteurs potentiels.  -->
                                    <?= getDatasAsHTMLOptions($companies, 'Sélectionner une entreprise', 'id_company', 'company_name'); ?>
                                </select>
                            </li>
                        <?php } else {
                            echo '<input type="hidden" name="campaign_company" value="' . $_SESSION['filter']['id_company'] . '">';
                        } ?>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="campaign_interlocutor">Interlocuteur</label>
                            <p class="small-text">Veuillez d'abord sélectionner une entreprise.</p>
                            <select class="form__input form__input--select" type="text" name="campaign_interlocutor" id="campaign_interlocutor" required aria-label="Sélectionner l'interlocuteur au sein de l'entreprise" value="<?php if (isset($_SESSION['form_data']) && isset($_SESSION['form_data']['id_interlocutor'])) {
                                                                                                                                                                                                                                        echo $_SESSION['form_data']['id_interlocutor'];
                                                                                                                                                                                                                                    } ?>">
                                <!-- Les options sont automatiquement générées en javascript quand l'entreprise est sélectionnée. -->
                                <?php
                                $interlocutors = fetchInterlocutors($dbCo, $_SESSION);
                                if (isset($_SESSION['filter']['id_company'])) {
                                    echo getInterlocutorsAsOptions($interlocutors);
                                }
                                ?>
                            </select>
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="user_TDC">Chargé de la campagne</label>
                            <select class="form__input form__input--select" type="text" name="user_TDC" id="user_TDC" required aria-label="Sélectionner le chargé de la campagne dans votre entreprise" value="<?php if (isset($_SESSION['form_data']) && isset($_SESSION['form_data']['id_user_TDC'])) {
                                                                                                                                                                                                                    echo $_SESSION['form_data']['id_user_TDC'];
                                                                                                                                                                                                                } ?>">
                                <option value="">- Sélectionner un chargé -</option>
                                <?php
                                $chargeOptions = '';
                                foreach ($nonClientUsers as $user) {
                                    $chargeOptions .= '<option value="' . $user['id_user'] . '"';

                                    if (isset($selectedCampaign['id_user_TDC']) && $user['id_user'] === $selectedCampaign['id_user_TDC']) {
                                        $chargeOptions .= ' selected';
                                    }

                                    $chargeOptions .= '>' . $user['firstname'] . ' ' . $user['lastname'];
                                }
                                echo $chargeOptions;
                                ?>

                                <!-- <?= getDatasAsHTMLOptions($nonClientUsers, 'Sélectionner un collaborateur', 'id_user', 'firstname', 'lastname') ?> -->
                            </select>
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="date_start">Début de la campagne</label>
                            <input class="form__input form__input--from" type="date" name="date_start" id="date_start" required aria-label="Sélectionner la date de début de l'opération" value="<?php if (isset($selectedCampaign['date_start'])) {
                                                                                                                                                                                                        echo formatDateForInput($selectedCampaign['date_start']);
                                                                                                                                                                                                    }
                                                                                                                                                                                                    if (isset($_SESSION['form_data']) && isset($_SESSION['form_data']['date_start'])) {
                                                                                                                                                                                                        echo $_SESSION['form_data']['date_start'];
                                                                                                                                                                                                    } ?>">
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="date_end">Fin de la campagne</label>
                            <input class="form__input form__input--to" type="date" name="date_end" id="date_end" required aria-label="Sélectionner la date de fin de l'opération" value="<?php if (isset($selectedCampaign['date_end'])) {
                                                                                                                                                                                                echo formatDateForInput($selectedCampaign['date_end']);
                                                                                                                                                                                            }
                                                                                                                                                                                            if (isset($_SESSION['form_data']) && isset($_SESSION['form_data']['date_end'])) {
                                                                                                                                                                                                echo $_SESSION['form_data']['date_end'];
                                                                                                                                                                                            }  ?>">
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="budget">Budget investi (sans €)</label>
                            <input class="form__input form__input--number" type="number" name="budget" id="budget" placeholder="12500" required aria-label="Saississez le budget de la nouvelle campagne ou 0 si il n'a pas encore été défini." value="<?php
                                                                                                                                                                                                                                                        if (isset($_GET['myc']) && intval($_GET['myc'])) {
                                                                                                                                                                                                                                                            echo $selectedCampaign['budget'];
                                                                                                                                                                                                                                                        } else if (isset($_SESSION['form_data']) && isset($_SESSION['form_data']['budget']) && $_SESSION['form_data']['budget'] >= 0) {
                                                                                                                                                                                                                                                            echo $_SESSION['form_data']['budget'];
                                                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                                                            echo '0';
                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                        ?>">
                        </li>
                        <?php
                        if (isset($_GET['myc']) && intval($_GET['myc'])) {
                            // Les inputs dans le cas où il s'agit d'une modification de campagne. 
                            echo '<input class="button button--new-campaign" type="submit" value="Modifier campagne" aria-label="Valider la création de la nouvelle campagne">';
                            echo '<input type="hidden" name="action" value="modify-campaign">';
                            echo '<input type="hidden" name="id_campaign" value="' . $selectedCampaign['id_campaign'] . '">';
                        } else {
                            // Les inputs dans le cas où il s'agit d'une création de campagne.
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