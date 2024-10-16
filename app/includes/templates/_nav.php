<?php

/**
 * Fetch a different nav if the user is connected or not.
 *
 * @param string $dashboardActive - Makes dashboard active.
 * @param string $profilActive - Makes profil active.
 * @return string - Class name that will be applied to the navigation menu.
 */
function fetchNav(array $session, string $dashboardActive = '', string $NetworkActive = '', string $historyActive = '', string $profilActive = ''): string
{
    if (isset($_SESSION['id_user'])) {
        return '
            <ul class="nav__lst" id="nav-list">
                <li class="nav__itm ' . $dashboardActive . '">
                    <a href="/dashboard.php" class="nav__lnk nav__lnk--dashboard" aria-label="Lien vers le tableau de bord contenant les campagnes de communications de l\'année en cours">Tableau de bord</a>
                </li>'
            . displayNetworkLinkIfTDC($session, $NetworkActive) .
            displayHistoryLinkIfPermissionOK($session, $historyActive) .
            '<li class="nav__itm ' . $profilActive . '">
<<<<<<< HEAD
                    <a href="/profil.php" class="nav__lnk nav__lnk--profile" aria-label="Lien vers mon profil d\'utilisateur">Mon profil</a>
=======
                    <a href="/profil" class="nav__lnk nav__lnk--profile" aria-label="Lien vers mon profil d\'utilisateur">Mon profil</a>
>>>>>>> master
                </li>
                <li class="nav__itm">
                    <a href="logout.php" class="nav__lnk nav__lnk--logout" aria-label="Se déconnecter de l\'application">Déconnexion</a>
                </li>
            </ul>
        ';
    } else {
        return '
            <ul class="nav__lst" id="nav-list">
                <li class="nav__itm">
                    <a href="#connection-form" id="connection-menu" class="nav__lnk nav__lnk--login" aria-label="Lien vers le formulaire de connexion à l\'application">Se connecter</a>
                </li>
                <li class="nav__itm">
                    <a href="https://www.toiledecom.fr/contactez-nous/" class="nav__lnk nav__lnk--contact" aria-label="Lien vers un formulaire de contact">Nous contacter</a>
                </li>
            </ul>
        ';
    }
}


/**
 * Displays a special link to network if user is not a client.
 *
 * @param array $session - Superglobal $_SESSION.
 * @return string - HTML code for the link.
 */
function displayNetworkLinkIfTDC(array $session, string $NetworkActive): string
{
    if (isset($session['client']) && $session['client'] === 0) {
        return '
        <li class="nav__itm ' . $NetworkActive . ' dropdown">
            <button class="nav__lnk nav__lnk--network dropdown__button" aria-label="Menu déroulant" id="dropdown-btn">Mon réseau<span class="nav__arrow">▼</span></button>
            <ul class="dropdown__child" id="dropdown-child">
                <li class="dropdown__child-itm">
                    <a href="/clients.php" class="dropdown__child-lnk" aria-label="Lien vers mes clients">Mes clients</a>
                </li>
                <li class="dropdown__child-itm">
                    <a href="/partners.php" class="dropdown__child-lnk" aria-label="Lien vers mes partenaires">Mes partenaires</a>
                </li>
                <li class="dropdown__child-itm">
                    <a href="/new-user.php" class="dropdown__child-lnk" aria-label="Lien vers création d\'un nouvel utilisateur client ou Toile de Com">Nouvel utilisateur</a>
                </li>
            </ul>
        </li>';
    } else {
        return '';
    }
}


/**
 * Displays a special link to history if user is not a client or if he is a client/boss.
 *
 * @param array $session - Superglobal $_SESSION.
 * @return string - HTML code for the link.
 */
function displayHistoryLinkIfPermissionOK(array $session, string $historyActive): string
{
    if (isset($session['client']) && $session['client'] === 0 || $session['boss'] === 1) {
        return '
            <li class="nav__itm ' . $historyActive . '">
                <a href="/history.php" class="nav__lnk nav__lnk--history" aria-label="Lien vers l\'historique des campagnes">Historique</a>
            </li>';
    } else {
        return '';
    }
}
