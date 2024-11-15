<?php

/**
 * Fetch a different nav if the user is connected or not.
 *
 * @param string $dashboardActive - Makes dashboard active.
 * @param string $profilActive - Makes profil active.
 * @return string - Class name that will be applied to the navigation menu.
 */
function fetchNav(array $session, array $companies = [], string $dashboardActive = '', string $newCampaignActive = '', string $clientActive = '', string $myAgencyActive = '', string $historyActive = '', string $profilActive = ''): string
{
    if (isset($_SESSION['id_user'])) {
        return '
            <ul class="nav__lst" id="nav-list">
                <li class="nav__itm ' . $dashboardActive . '">
                    <a href="/dashboard" class="nav__lnk nav__lnk--dashboard" aria-label="Lien vers le tableau de bord contenant les campagnes de communications de l\'année en cours">Tableau de bord</a>
                </li>'
            . displayNetworkLinkIfTDC($session, $newCampaignActive, $clientActive, $myAgencyActive, $companies) .
            displayHistoryLinkIfPermissionOK($session, $historyActive) .
            '<li class="nav__itm ' . $profilActive . '">
                    <a href="/profil" class="nav__lnk nav__lnk--profile" aria-label="Lien vers mon profil d\'utilisateur">Mon profil</a>
                </li>
                <li class="nav__itm">
                    <a href="logout" class="nav__lnk nav__lnk--logout" aria-label="Se déconnecter de l\'application">Déconnexion</a>
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
function displayNetworkLinkIfTDC(array $session, string $newCampaignActive, string $clientActive, string $myAgencyActive, array $companies): string
{
    if (isset($session['client']) && $session['client'] === 0) {
        return '
       <li class="nav__itm ' . $clientActive . ' dropdown">
    <button class="nav__lnk nav__lnk--network dropdown__button" aria-label="Menu déroulant" id="dropdown-btn-client">Mes clients<span class="nav__arrow">▼</span></button>
    <ul class="dropdown__child dropdown__grid" id="dropdown-child-client">
        <li class="dropdown__child-itm">
            <a href="/clients" class="dropdown__child-lnk" aria-label="Lien vers mes clients">Tous mes clients</a>
        </li>
        ' . getAllClientsAsLnk($companies) . '
    </ul>
</li>
<li class="nav__itm ' . $newCampaignActive . '">
    <a href="/new-campaign" class="nav__lnk nav__lnk--new-campaign" aria-label="Redirige vers un formulaire de création de campagne">Nouvelle campagne</a>
</li>
<li class="nav__itm ' . $myAgencyActive . ' dropdown">
    <button class="nav__lnk nav__lnk--agency dropdown__button" aria-label="Menu déroulant" id="dropdown-btn-agency">Mon agence<span class="nav__arrow">▼</span></button>
    <ul class="dropdown__child" id="dropdown-child-agency">
        <li class="dropdown__child-itm">
            <a href="/my-agency" class="dropdown__child-lnk" aria-label="Lien vers mon agence">Mon équipe</a>
        </li>
        <li class="dropdown__child-itm">
            <a href="/partners" class="dropdown__child-lnk" aria-label="Lien vers les partenaires de l\'agence">Nos partenaires</a>
        </li>
    </ul>
</li>
        ';
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
    if (isset($session['client']) && $session['client'] === 1 && $session['boss'] === 1) {
        return '
            <li class="nav__itm ' . $historyActive . '">
                <a href="/history?client=' . $session['id_company'] . '" class="nav__lnk nav__lnk--history" aria-label="Lien vers l\'historique des campagnes">Historique</a>
            </li>';
    } else {
        return '';
    }
}

/**
 * Get all clients as lnk for dropdown items.
 *
 * @param array $companies - An array containing the list of clients
 * @return string - The list of clients or empty string. 
 */
function getAllClientsAsLnk(array $companies): string
{
    $html = '';

    foreach ($companies as $company) {
        if ($company['id_company'] != 1) {
            $html .= '<li class="dropdown__child-itm"><a href="my-client?client=' . $company['id_company'] . '" class="dropdown__child-lnk" aria-label="Lien vers mes clients">' . $company['company_name'] . '</a></li>';
        }
    }

    return $html;
}
