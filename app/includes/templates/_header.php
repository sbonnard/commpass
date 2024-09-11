<?php

/**
 * Template for header's index.
 *
 * @param string $url - The url the header title will lead you too.
 * @param string|null $linkTitle - The <a> title so people now where the link leads when they mouseover it.
 * @return string - header's content for index.php, HTML elements
 */
function fetchHeader(string $url, string $linkTitle = null): string
{
    return '
        <a href="' . $url . '" title="' . $linkTitle . '">
            <h1 class="header__ttl">WellComm</h1>
        </a>
        <div class="hamburger">
            <a href="#menu" id="hamburger-menu-icon">
                <img src="img/hamburger.svg" alt="Menu Hamburger">
            </a>
        </div>
    ';
}


/**
 * Template for a login form.
 *
 * @param array $session - Superglobal $_SESSION.
 * @return string - HTML element to construct login form.
 */
function fetchLogInForm(array $session): string
{
    return '
    <form class="form login__menu" action="login.php" method="post" id="connection-form" aria-label="Formulaire de connexion">
        <div class="login__section">
            <h2 class="header__ttl login__ttl">WellComm</h2>
            <a class="button--close" href="index.php" aria-label="Fermer le formulaire de connexion">
                <img src="img/close-btn.svg" alt="Bouton fermer">
            </a>
        </div>

        <ul class="form__lst">
            <li class="form__itm">
                <label for="username" aria-label="Saississez votre nom d\'ilisateur">Nom d\'utilisateur</label>
                <input class="form__input" type="text" name="username" id="username" placeholder="alemaitre78" required>
            </li>
            <li class="form__itm">
                <label for="password" aria-label="Saississez votre mot de passe">Mot de passe</label>
                <div class="form__input--password">
                    <input class="form__input" type="password" name="password" id="password" placeholder="•••••••••••" required>
                    <button class="button--eye button--eye--inactive" id="eye-button" aria-label="Montrer le mot de passe en clair dans le champs de saisie"></button>
                </div>
            </li>
        </ul>
        <input class="button button--connection" type="submit" value="Connexion">
        <input type="hidden" name="token" value="' . $session['token'] . '">
        <input type="hidden" name="action" value="log-in">
    </form>
    ';
}
