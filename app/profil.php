<?php
session_start();

require_once "includes/_config.php";
require_once "includes/_database.php";
require_once "includes/_functions.php";
require_once "includes/_security.php";
require_once "includes/_message.php";
require_once "includes/templates/_head.php";
require_once "includes/templates/_header.php";
require_once "includes/templates/_forms.php";
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
        <div class="notifs">
            <?php
            echo getErrorMessage($errors);
            echo getSuccessMessage($messages);
            ?>
        </div>
        
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
                <div class="profil__modify--lnk-list">
                    <button class="profil__lnk" id="button-email">email</button>
                    <span aria-hidden="true"> | </span>
                    <button class="profil__lnk" id="button-tel">téléphone</button>
                    <span aria-hidden="true"> | </span>
                    <button class="profil__lnk profil__lnk--active" id="button-pwd">mot de passe</button>
                </div>
                <?= getModifyEmailForm() ?>
                <?= getModifyPhoneForm() ?>
                <?= getModifyPwdForm() ?>
            </section>
        </div>

    </main>

    <footer class="footer">
        <?= fetchFooter() ?>
    </footer>
</body>

<script type="module" src="js/script.js"></script>
<script type="module" src="js/profil-forms.js"></script>

</html>