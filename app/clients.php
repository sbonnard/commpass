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
        <?= fetchNav($_SESSION, $companies, '', '', 'nav__itm--active') ?>
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
            <a href="/new-client" class="button button--add--solid" aria-label="Redirige vers un formulaire de création de client">Nouveau client</a>
            <!-- <span class="text-tertiary"><a href="/new-user" class="button button--user" aria-label="Redirige vers un formulaire de création d'utilisateur">Nouvel utilisateur</a></span> -->
        </div>

        <div class="card <?php
                            if (!empty($companies)) {
                                echo 'card--grid card--grid--3columns';
                            }
                            ?>">
            <?php

            // Récupérer les données des entreprises et les afficher sous forme de cartes.
            $companyDatas = '';
            if (!empty($companies) && is_array($companies)) {
                foreach ($companies as $company) {
                    if ($company['id_company'] !== $_SESSION['id_company']) {
                        // Génère une carte d'une entreprise cliente.
                        $companyDatas .= '
        <div class="card" data-card="">
            <section class="card__section card__section--company" aria-labelledby="company_name' . $company['id_company'] . '">
                <a href="my-client?client=' . $company['id_company'] . '"><h3 class="client__ttl" id="company_name' . $company['id_company'] . '">' . $company['company_name'] . '</h3></a>
                <ul class="client__lst gradient-border gradient-border--top">';

                        // Vérifie si on trouve un utilisateur dans cette entreprise
                        $userFound = false;

                        // Boucle pour afficher tous les utilisateurs liés à l'entreprise. 
                        foreach ($users as $user) {
                            if ($user['id_company'] === $company['id_company']) {
                                $userFound = true;
                                $companyDatas .= '<li class="client__name ';

                                if ($user['boss'] === 1) {
                                    // Si l'utilisateur est gérant de l'entreprise cliente, on applique un style particulier à son nom 
                                    $companyDatas .= ' user--boss ';
                                }

                                $companyDatas .= '"';

                                if ($user['enabled'] === 0) {
                                    // Si l'utilisateur a été désactivé, son nom s'affiche en gris.
                                    $companyDatas .= ' style="color: #b5b5b5c9;"';
                                }

                                // Affichage du prénom et nom de l'utilisateur.
                                $companyDatas .= '>' . $user['firstname'] . ' ' . $user['lastname'];

                                if ($user['enabled'] === 1) {
                                    // Si l'utilisateur est autorisé, bouton de désactivation affiché. 
                                    $companyDatas .=
                                        '<form class="client__disabled-form" method="post" action="actions" onsubmit="return confirmDisable()">
                                    <button type="submit" class="client--disable-btn" data-client-disable="' . $user['id_user'] . '"></button>
                                    <input type="hidden" name="token" value="' . $_SESSION['token'] . '">
                                    <input type="hidden" name="action" value="disable-client">
                                    <input type="hidden" name="client-user" value="' . $user['id_user'] . '">
                                </form>';
                                } else {
                                    // Si l'utilisateur n'est pas autorisé, bouton d'activation affiché. 
                                    $companyDatas .=
                                        '<form class="client__enable-form" method="post" action="actions" onsubmit="return confirmEnable()">
                                    <button type="submit" class="client--enable-btn" data-client-enable="' . $user['id_user'] . '"></button>
                                    <input type="hidden" name="token" value="' . $_SESSION['token'] . '">
                                    <input type="hidden" name="action" value="enable-client">
                                    <input type="hidden" name="client-user" value="' . $user['id_user'] . '">
                                </form>';
                                }

                                $companyDatas .= '</li>';
                            }
                        }

                        if (!$userFound) {
                            // Si aucun utilisateur dans l'entreprise, affiche un texte.
                            $companyDatas .= '<li>Aucun interlocuteur pour cette entreprise.</li>';
                        }

                        $companyDatas .= '</ul>';

                        if ($company['unique_brand'] === 0) {
                            $companyDatas .= '<ul>';
                            $companyDatas .= '<h4 class="client__subttl">Les marques</h4>';


                            // Boucle sur les marques de l'entreprise pour les afficher. 
                            foreach ($allbrands as $brand) {
                                if ($brand['id_company'] === $company['id_company']) {
                                    $companyDatas .= '<li class="campaign__legend"><span class="campaign__legend-square" style="background-color:' . $brand['legend_colour_hex'] . '"></span>' . $brand['brand_name'] . '</li>';
                                }
                            }

                            if (empty($allbrands)) {
                                $companyDatas .= '<li>Aucune marque pour cette entreprise.</li>';
                            }

                            $companyDatas .= '</ul>';
                        }

                        $companyDatas .= '</section></div>';
                    }
                }
            } else {
                // Si Toile de Com n'a aucun client, message affiché. 
                echo '
                <div class="card card__section">
                <p class="big-text">Aucun client pour l\'instant !</p>
                </div>';
            }
            echo $companyDatas;
            ?>
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
<?php
// LE SCRIPT DE DROPDOWN N'EST UTILE QUE POUR LES UTILISATEURS NON-CLIENTS
if (isset($_SESSION['client']) && $_SESSION['client'] === 0) {
    echo '<script type="module" src="js/dropdown-menu.js"></script>';
}
?>
<script type="module" src="js/cards.js"></script>
<script>
    // Confirmation de blocage d'un utilisateur.
    function confirmDisable() {
        return confirm("Êtes-vous sûr de vouloir rendre ce compte utilisateur inactif ?");
    }

    // Confirmation de déblocage d'un utilisateur.
    function confirmEnable() {
        return confirm("Êtes-vous sûr de vouloir réactiver ce compte utilisateur ?");
    }
</script>