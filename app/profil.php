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
        <?= fetchNav('', 'nav__itm--active') ?>
    </nav>

    <main class="container container--campaigns container__flex">
        <div class="card">
            <h2 class="ttl" id="profil">Mon profil</h2>

            <section class="card__section profil" aria-labelledby="profil">
                <p><span class="profil__info">Entreprise : </span><?= getCompanyName($dbCo, $_SESSION) ?></p>
                <p><span class="profil__info">Nom : </span><?= $user['firstname'] . ' ' . $user['lastname'] ?></p>
                <p><span class="profil__info">Email : </span><?= $user['email'] ?></p>
                <p><span class="profil__info">Tél. : </span><?= $user['phone'] ?></p>
            </section>
        </div>

        <div class="card">
            <h2 class="ttl" id="modify-infos">Modifier</h2>
            <section class="card__section profil__modify" aria-labelledby="profil">
                <div>
                    <button class="profil__lnk" href="">email</button>
                    <span aria-hidden="true"> | </span>
                    <button class="profil__lnk" href="">téléphone</button>
                    <span aria-hidden="true"> | </span>
                    <button class="profil__lnk" href="">mot de passe</button>
                </div>
            </section>
        </div>

    </main>

    <footer class="footer">
        <?= fetchFooter() ?>
    </footer>
</body>

<script type="module" src="js/script.js"></script>
<script type="module" src="js/password.js"></script>

</html>