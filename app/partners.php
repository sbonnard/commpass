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
        <?= fetchNav($_SESSION, '', 'nav__itm--active') ?>
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

        <div class="button__section">
            <a href="new-client.php" class="button button--add--solid" aria-label="Redirige vers un formulaire de création de client">Ajouter partenaire</a>
        </div>

        <div class="card">
            <section class="card__section" aria-labelledby="partners-ttl">
                <?php
                $partnersDatas = '';

                $partnersDatas .= '<ul>';
                foreach ($partners as $partner) {
                    $partnersDatas .= '<li class="partner medium-text">' . $partner['partner_name'] . '</li>';
                }
                $partnersDatas .= '</ul>';

                echo $partnersDatas;
                ?>

                <form action="api.php" method="post" class="form gradient-border gradient-border--top" aria-label="Formulaire d'ajout d'un nouveau partenaire.">
                    <ul class="form__lst form__lst--app">
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="partner_name">Ajouter un partenaire</label>
                            <input class="form__input" type="text" name="partner_name" id="partner_name" placeholder="Tendance Ouest" required>
                        </li>
                        <input class="button button--partner" type="submit" value="Créer partenaire">
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
<script type="module" src="js/cards.js"></script>