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

checkUserClientStatus($_SESSION);

unsetFilters($_SESSION);
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
        <?= fetchNav($_SESSION, $companies, 'nav__itm--active') ?>
    </nav>

    <main class="container container--campaigns container__flex">
        <div class="notifs">
            <?php
            echo getErrorMessage($errors);
            echo getSuccessMessage($messages);
            ?>
        </div>

        <div class="card big-padding">
            <h2 class="ttl lineUp" id="new-client-ttl">Nouveau client</h2>

            <section class="card__section" aria-labelledby="new-client-ttl">
                <form class="form" action="actions" method="post" aria-label="Formulaire de création d'un nouveau client">
                    <ul class="form__lst form__lst--app">
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="company_name">Nom de l'entreprise</label>
                            <input class="form__input form__input--number" type="text" name="company_name" id="company_name" placeholder="FakeBusiness" required aria-label="Entrez le nom de l'entreprise cliente." autofocus>
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="annual_budget">Budget annuel (sans €)</label>
                            <p class="small-text">Le budget annuel peut être défini plus tard</p>
                            <input class="form__input form__input--number" type="text" name="annual_budget" id="annual_budget" placeholder="12500" required aria-label="Budget annuel s'il a été défini, sinon 0." value="0">
                        </li>
                        <input type="hidden" name="year" value="<?= $currentYear ?>">
                        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                        <input type="hidden" name="action" value="create_client">
                        <input class="button button--add" type="submit" value="Créer le client" aria-label="Valider la création du client">
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

<?php
// LE SCRIPT DE DROPDOWN N'EST UTILE QUE POUR LES UTILISATEURS NON-CLIENTS
if (isset($_SESSION['client']) && $_SESSION['client'] === 0) {
    echo '<script type="module" src="js/dropdown-menu.js"></script>';
}
?>

<script type="module" src="js/ajax.js"></script>

</html>