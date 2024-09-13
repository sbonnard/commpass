<?php
session_start();

//CONFIG AND CONNECTION
require_once "includes/_config.php";
require_once "includes/_database.php";

// FUNCTIONS
require_once "includes/_functions.php";
require_once "includes/_security.php";
require_once "includes/_message.php";

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
        <?= fetchNav() ?>
    </nav>

    <main class="container container--campaigns container__flex">

        <div class="card">
            <h2 class="ttl" id="new-campaign-ttl">Nouvelle Campagne</h2>

            <section class="card__section" aria-labelledby="new-campaign-ttl">
                <form class="form" action="actions.php" method="post" aria-label="Formulaire de création d'une nouvelle campagne">
                    <ul class="form__lst form__lst--app">
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="campaign_name">Nom de la campagne</label>
                            <input class="form__input" type="text" name="campaign_name" id="campaign_name" placeholder="Soldes d'Hiver" required aria-label="Saississez le nom de la nouvelle campagne">
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="company" aria-label="Sélectionner l'entreprise concernée">Entreprise</label>
                            <select class="form__input form__input--select" type="text" name="company" id="company" required aria-label="Sélectionner l'entreprise lançant une nouvelle campagne">

                            </select>
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="interlocutor">Interlocuteur</label>
                            <select class="form__input form__input--select" type="text" name="interlocutor" id="interlocutor" required aria-label="Sélectionner l'interlocuteur au sein de l'entreprise">

                            </select>
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="budget" aria-label="Saississez le nom de la nouvelle campagne">Budget investi (sans €)</label>
                            <input class="form__input form__input--number" type="number" name="budget" id="budget" placeholder="12500" required>
                        </li>
                        <input class="button button--new-campaign" type="submit" value="Créer la campagne" aria-label="Valider la création de la nouvelle campagne">
                        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                        <input type="hidden" name="action" value="create-campaign">
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

</html>