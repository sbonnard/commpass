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

        <h2 class="ttl lineUp">
            Les clients de<br>
            <span class="ttl--tertiary"><?= getCompanyName($dbCo, $_SESSION) ?></span>
        </h2>

        <div class="button__section">
            <a href="new-client.php" class="button button--add--solid" aria-label="Redirige vers un formulaire de création de client">Nouveau client</a>
            <span class="text-tertiary"><a href="new-user.php" class="button button--user" aria-label="Redirige vers un formulaire de création d'utilisateur">Nouvel utilisateur</a></span>
        </div>

        <div class="card card--grid card--grid--3columns">
            <?php

            // Récupérer les données des entreprises et les afficher sous forme de cartes.
            $companyDatas = '';

            foreach ($companies as $company) {
                if ($company['id_company'] !== $_SESSION['id_company']) {
                    $companyDatas .= '
        <div class="card">
            <section class="card__section card__section--company" aria-labelledby="company_name' . $company['id_company'] . '">
                <h3 class="" id="company_name' . $company['id_company'] . '">' . $company['company_name'] . '</h3>
                <ul>';

                    $userFound = false;

                    foreach ($users as $user) {
                        if ($user['id_company'] === $company['id_company']) {
                            $userFound = true;
                            $companyDatas .= '<li class="';

                            if ($user['boss'] === 1) {
                                $companyDatas .= 'user--boss';
                            }

                            $companyDatas .= '">' . $user['firstname'] . ' ' . $user['lastname'] . '</li>';
                        }
                    }

                    if (!$userFound) {
                        $companyDatas .= '<li>Aucun interlocuteur pour cette entreprise.</li>';
                    }

                    $companyDatas .= '</ul></section></div>';
                }
            }

            echo $companyDatas;
            ?>
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