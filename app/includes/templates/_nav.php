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
                    <a href="dashboard.php" class="nav__lnk nav__lnk--dashboard" aria-label="Lien vers le tableau de bord contenant les campagnes de communications de l\'année en cours">Tableau de bord</a>
                </li>'
            . displayNetworkLinkIfTDC($session, $NetworkActive) .
            '<li class="nav__itm ' . $historyActive . '">
                    <a href="history.php" class="nav__lnk nav__lnk--history" aria-label="Lien vers l\'historique des campagnes">Historique</a>
                </li>
                <li class="nav__itm ' . $profilActive . '">
                    <a href="profil.php" class="nav__lnk nav__lnk--profile" aria-label="Lien vers mon profil d\'utilisateur">Mon profil</a>
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
                    <a href="contact.php" class="nav__lnk nav__lnk--contact" aria-label="Lien vers un formulaire de contact">Nous contacter</a>
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
function displayNetworkLinkIfTDC(array $session, string $clientActive): string
{
    if (isset($session['client']) && $session['client'] === 0) {
        return '
        <li class="nav__itm dropdown ' . $clientActive . '">
            <button class="nav__lnk nav__lnk--network dropdown__button" aria-label="Menu déroulant">Mon réseau<span class="nav__arrow">▼</span></button>
            <ul class="dropdown__child">
                <li class="dropdown__child-itm">
                    <a href="clients.php" class="dropdown__child-lnk" aria-label="Lien vers mes clients">Mes clients</a>
                </li>
                <li class="dropdown__child-itm">
                    <a href="collaborators.php" class="dropdown__child-lnk" aria-label="Lien vers mes collaborateurs">Mes collaborateurs</a>
                </li>
                <li class="dropdown__child-itm">
                    <a href="partners.php" class="dropdown__child-lnk" aria-label="Lien vers mes partenaires">Mes partenaires</a>
                </li>
            </ul>
        </li>';
    } else {
        return '';
    }
}
