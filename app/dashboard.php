<?php
session_start();

require_once "includes/_config.php";
require_once "includes/_database.php";
require_once "includes/_functions.php";
require_once "includes/_security.php";
require_once "includes/_message.php";
require_once "includes/templates/_head.php";
require_once "includes/templates/_header.php";
require_once "includes/templates/_footer.php";
require_once "includes/templates/_nav.php";

generateToken();

checkConnection($_SESSION);

$user = fetchUserDatas($dbCo, $_SESSION);

$campaigns = getCompanyCampaigns($dbCo, $_SESSION);

$brands = getCampaignsBrands($dbCo, $_SESSION, $campaigns);
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
        <?= fetchNav('nav__itm--active') ?>
    </nav>

    <main class="container container--campaigns container__flex">
        <h2 class="ttl">
            Bonjour <?= $user['firstname'] ?><br>
            <span class="ttl--tertiary"><?= getCompanyName($dbCo, $_SESSION) ?></span>
        </h2>

        <section class="card campaign">
            <?= getMessageIfNoCampaign($campaigns) ?>
            <?= getCampaignTemplate($dbCo, $campaigns, $brands, $_SESSION) ?>

            <?php 
            // var_dump($brands);
            // var_dump($campaigns);
             ?>

        </section>
    </main>

    <footer class="footer">
        <?= fetchFooter() ?>
    </footer>
</body>

<script type="module" src="js/script.js"></script>
<script type="module" src="js/password.js"></script>

</html>