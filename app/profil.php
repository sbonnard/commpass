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
        echo fetchHeader('dashboard.php', 'Mon tableau de bord');
        ?>
    </header>

    <nav class="nav hamburger__menu" id="menu" aria-label="Navigation principale du site">
        <?= fetchNav($_SESSION, $companies, '', '', '', '', '', 'nav__itm--active') ?>
    </nav>


    <main class="container container--campaigns container__flex">
        <div class="notifs">
            <?php
            echo getErrorMessage($errors);
            echo getSuccessMessage($messages);
            ?>
        </div>

        <div class="card">
            <h2 class="ttl lineUp" id="profil">Mon profil</h2>

            <section class="card__section profil" aria-labelledby="profil">
                <p><span class="profil__info">Entreprise : </span><?= getCompanyName($dbCo, $_SESSION) ?></p>
                <p><span class="profil__info">Nom : </span><?= $user['firstname'] . ' ' . $user['lastname'] ?></p>
                <p><span class="profil__info">Email : </span><?= $user['email'] ?></p>
                <p><span class="profil__info">Tél. : </span><?= formatPhoneNumber($user['phone']) ?></p>
            </section>
        </div>

        <?php
        if (isset($_SESSION['client']) && $_SESSION['client'] === 1 && $_SESSION['boss'] === 1) {
            echo
            '<div class="card">
                <h2 class="ttl lineUp" id="my-brands">Mes marques</h2>
                <section class="card__section profil__modify" aria-labelledby="my-brands">
                    <ul class="profil__brands">
                        ' . getBrandsAsList(fetchCompanyBrands($dbCo, $_SESSION)) . '
                    </ul>
                    <form class="form" action="actions" method="post">
                        <ul class="form__lst form__lst--app">
                            <li class="form__itm form__itm--app">
                                <label class="form__label" for="profile_brand" aria-label="Sélectionner la marque concernée">Sélectionnez la marque à modifier</label>
                                <select class="form__input form__input--select" type="text" name="profile_brand" id="profile_brand" required aria-label="Sélectionner l\'entreprise lançant une nouvelle campagne">
                                    <option value="">- Sélectionner une marque -</option>
                                    ' . getCompanyBrandsAsHTMLOptions(fetchCompanyBrands($dbCo, $_SESSION)) . '
                                </select>
                            </li>
                            <li class="form__itm form__itm--app">
                                <label class="form__label" for="color" aria-label="Sélectionner la couleur pour la marque">Nouvelle couleur de la marque</label>
                                <input class="form__input--colour" type="color" name="color" id="color" value="">
                            </li>
                            <input class="button button--confirm" type="submit" value="Confirmer" aria-label="Confirmer la modification de la couleur">
                            <input type="hidden" name="token" value="' . $_SESSION['token'] . '">
                            <input type="hidden" name="action" value="modify-colour">
                        </ul>
                    </form>
                </section>
            </div>';
        }
        ?>

        <div class="card">
            <h2 class="ttl lineUp" id="modify-infos">Modifier</h2>
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

    <a class="button--up" href="#" aria-label="Renvoie en haut de la page." id="scrollTop">
        <img src="img/arrow-up.svg" alt="Flèche vers le haut">
    </a>

    <footer class="footer">
        <?= fetchFooter() ?>
    </footer>
</body>

<script type="module" src="js/script.js"></script>
<script type="module" src="js/burger.js"></script>
<script type="module" src="js/dropdown-menu.js"></script>
<script type="module" src="js/profil-forms.js"></script>

</html>