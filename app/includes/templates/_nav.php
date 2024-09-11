<?php

/**
 * Fetch a different nav if the user is connected or not.
 *
 * @return string
 */
function fetchNav():string {
    if(isset($_SESSION['id_user'])) {
        return '
            <ul class="nav__lst" id="nav-list">
                <li class="nav__itm">
                    <a href="campaigns.php" class="nav__lnk" aria-label="Lien vers mes campagnes de communications">Mes campagnes</a>
                </li>
                <li class="nav__itm">
                    <a href="profil.php" class="nav__lnk" aria-label="Lien vers mon profil d\'utilisateur">Mon profil</a>
                </li>
                <li class="nav__itm">
                    <a href="logout.php" class="nav__lnk" aria-label="Se déconnecter de l\'application">Déconnexion</a>
                </li>
            </ul>
        ';
    } else {
        return '
            <ul class="nav__lst" id="nav-list">
                <li class="nav__itm">
                    <a href="#connection-form" id="connection-menu" class="nav__lnk" aria-label="Lien vers le formulaire de connexion à l\'application">Se connecter</a>
                </li>
                <li class="nav__itm">
                    <a href="contact.php" class="nav__lnk" aria-label="Lien vers un formulaire de contact">Nous contacter</a>
                </li>
            </ul>
        ';
    }
}