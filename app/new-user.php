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
        <?= fetchNav($_SESSION, '', 'nav__itm--active') ?>
    </nav>

    <main class="container container--campaigns container__flex">
        <div class="notifs">
            <?php
            echo getErrorMessage($errors);
            echo getSuccessMessage($messages);
            ?>
        </div>

        <div class="card">
            <h2 class="ttl lineUp" id="new-user-ttl">Nouvel utilisateur</h2>

            <section class="card__section" aria-labelledby="new-user-ttl">
                <form class="form" action="actions" method="post" aria-label="Formulaire de création d'un nouvel utilisateur">
                    <ul class="form__lst form__lst--app">
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="username">Nom d'utilisateur</label>
                            <input class="form__input" type="text" name="username" id="username" placeholder="user94" required aria-label="Entrez le nom de l'utilisateur.">
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="firstname">Prénom</label>
                            <input class="form__input" type="text" name="firstname" id="firstname" placeholder="Marty" required aria-label="Entrez le prénom de l'utilisateur.">
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="lastname">Nom</label>
                            <input class="form__input" type="text" name="lastname" id="lastname" placeholder="McFly" required aria-label="Entrez le nom de famille de l'utilisateur.">
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="company" aria-label="Sélectionner l'entreprise concernée">Entreprise</label>
                            <select class="form__input form__input--select" type="text" name="company" id="company" required aria-label="Sélectionner l'entreprise pour laquelle l'utilisateur travail">
                                <?= getDatasAsHTMLOptions($companies, 'Sélectionner une entreprise', 'id_company', 'company_name'); ?>
                            </select>
                        </li>
                        <li class="form__itm form__itm--app form__itm--checkbox">
                            <label class="form__label form__label--root" for="boss">L'utilisateur est gérant de l'entreprise</label>
                            <input type="hidden" name="boss" value="0">
                            <input class="form__input--checkbox" type="checkbox" name="boss" id="boss" aria-label="Cocher si l'utilisateur est le gérant de l'entreprise." value="1">
                        </li>
                        <li class="form__itm form__itm--app">
                            <label for="password">Mot de passe</label>
                            <div class="form__input--password">
                                <input class="form__input" type="password" name="password" id="password" placeholder="•••••••••••" required aria-label="Saississez votre mot de passe">
                                <button class="button--eye button--eye--inactive" id="eye-button" aria-label="Montrer le mot de passe en clair dans le champs de saisie"></button>
                            </div>
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="email">Email</label>
                            <input class="form__input" type="email" name="email" id="email" placeholder="marty-mcfly@hillvalley.com" required aria-label="Entrez l'email de l'utilisateur.">
                        </li>
                        <li class="form__itm form__itm--app">
                            <label class="form__label" for="phone">Téléphone</label>
                            <input class="form__input" type="tel" name="phone" id="phone" placeholder="0688120668" required aria-label="Entrez le numéro de téléphone de l'utilisateur.">
                        </li>

                        <input type="hidden" name="status" id="status" value="">
                        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                        <input type="hidden" name="action" value="create_user">
                        <input class="button button--user" type="submit" value="Créer l'utilisateur" aria-label="Valider la création de l'utilisateur">
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
<script type="module" src="js/ajax-new-user.js"></script>
<script type="module" src="js/password.js"></script>

</html>