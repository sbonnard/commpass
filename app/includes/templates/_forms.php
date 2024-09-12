<?php

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


/**
 * Get the HTML form for email address modification.
 *
 * @return string HTML form for email address modification.
 */
function getModifyEmailForm(): string
{
    return '
    <form class="form hidden" action="actions.php" method="post" id="email_form" aria-label="Formulaire de modification de l\'email utilisateur">
        <ul class="form__lst form__lst--profil">
            <li class="form__itm form__itm--profil">
                <label class="form__label" for="email" aria-label="Saississez votre nouvel email">Nouvel email</label>
                <input class="form__input" type="text" name="email" id="email" placeholder="mon-email@exemple.fr" required>
            </li>
            <input class="button button--confirm" type="submit" value="Confirmer" aria-label="Confirmer la modification de l\'email">
            <input type="hidden" name="token" value="' . $_SESSION['token'] . '">
            <input type="hidden" name="action" value="modify-email">
        </ul>
    </form>';
}


/**
 * Get the HTML form for phone modification.
 *
 * @return string HTML form for phone modification.
 */
function getModifyPhoneForm(): string
{
    return '
    <form class="form hidden" action="actions.php" method="post" id="phone_form" aria-label="Formulaire de modification du numéro de téléphone">
        <ul class="form__lst form__lst--profil">
            <li class="form__itm form__itm--profil">
                <label class="form__label" for="phone" aria-label="Saississez votre nouvel phone">Modifier le numéro de téléphone</label>
                <input class="form__input" type="tel" name="phone" id="phone" placeholder="0607080910" required>
            </li>
            <input class="button button--confirm" type="submit" value="Confirmer" aria-label="Confirmer la modification du numéro de téléphone">
            <input type="hidden" name="token" value="' . $_SESSION['token'] . '">
            <input type="hidden" name="action" value="modify-phone">
        </ul>
    </form>';
}


/**
 * Get the HTML form for Phone address modification.
 *
 * @return string HTML form for Phone address modification.
 */
function getModifyPwdForm(): string
{
    return '
    <form class="form" action="actions.php" method="post" id="pwd_form" aria-label="Formulaire de modification du mot de passe">
        <ul class="form__lst form__lst--profil">
            <li class="form__itm form__itm--profil">
                <label class="form__label" for="password" aria-label="Saississez votre nouveau mot de passe">Nouveau mot de passe</label>
                <input class="form__input" type="password" name="password" id="password" placeholder="•••••••••••" required>
            </li>
            <li class="form__itm form__itm--profil">
                <label class="form__label" for="password" aria-label="Confirmer votre nouveau mot de passe">Confirmer mot de passe</label>
                <input class="form__input" type="password" name="password-confirm" id="password-confirm" placeholder="•••••••••••" required>
            </li>
            <input class="button button--confirm" type="submit" value="Confirmer" aria-label="Confirmer la modification du mot de passe">
            <input type="hidden" name="token" value="' . $_SESSION['token'] . '">
            <input type="hidden" name="action" value="modify-pwd">
        </ul>
    </form>';
}


