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
    <form class="form login__menu" action="login" method="post" id="connection-form" aria-label="Formulaire de connexion">
        <div class="login__section">
            <h2 class="header__ttl login__ttl">Commpass</h2>
            <a class="button--close" href="/index" aria-label="Fermer le formulaire de connexion">
                <img src="img/close-btn.svg" alt="Bouton fermer">
            </a>
        </div>

        <ul class="form__lst">
            <li class="form__itm">
                <label for="username">Nom d\'utilisateur</label>
                <input class="form__input" type="text" name="username" id="username" placeholder="alemaitre78" required aria-label="Saississez votre nom d\'ilisateur">
            </li>
            <li class="form__itm">
                <label for="password">Mot de passe</label>
                <div class="form__input--password">
                    <input class="form__input" type="password" name="password" id="password" placeholder="•••••••••••" required aria-label="Saississez votre mot de passe">
                    <button type="button" class="button--eye button--eye--inactive" id="eye-button" aria-label="Montrer le mot de passe en clair dans le champs de saisie"></button>
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
    <form class="form hidden" action="actions" method="post" id="email_form" aria-label="Formulaire de modification de l\'email utilisateur">
        <ul class="form__lst form__lst--app">
            <li class="form__itm form__itm--app">
                <label class="form__label" for="email">Nouvel email</label>
                <input class="form__input" type="text" name="email" id="email" placeholder="mon-email@exemple.fr" required aria-label="Saississez votre nouvel email">
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
    <form class="form hidden" action="actions" method="post" id="phone_form" aria-label="Formulaire de modification du numéro de téléphone">
        <ul class="form__lst form__lst--app">
            <li class="form__itm form__itm--app">
                <label class="form__label" for="phone">Modifier le numéro de téléphone</label>
                <input class="form__input" type="tel" name="phone" id="phone" placeholder="0607080910" required aria-label="Saississez votre nouvel phone">
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
    <form class="form" action="actions" method="post" id="pwd_form" aria-label="Formulaire de modification du mot de passe">
        <ul class="form__lst form__lst--app">
            <li class="form__itm form__itm--app">
                <label class="form__label" for="password">Nouveau mot de passe</label>
                <input class="form__input" type="password" name="password" id="password" placeholder="•••••••••••" required aria-label="Saississez votre nouveau mot de passe">
            </li>
            <li class="form__itm form__itm--app">
                <label class="form__label" for="password-confirm">Confirmer mot de passe</label>
                <input class="form__input" type="password" name="password-confirm" id="password-confirm" placeholder="•••••••••••" required aria-label="Confirmer votre nouveau mot de passe">
            </li>
            <input class="button button--confirm" type="submit" value="Confirmer" aria-label="Confirmer la modification du mot de passe">
            <input type="hidden" name="token" value="' . $_SESSION['token'] . '">
            <input type="hidden" name="action" value="modify-pwd">
        </ul>
    </form>';
}


/**
 * Displays filter form for non clients users.
 *
 * @param array $companies - list of companies
 * @param array $communicationObjectives - list of communication objectives
 * @return string - the form
 */
function displayFilterFormNotClient(array $companies, array $communicationObjectives): string
{
    return
        '<div class="card">
                <form class="card__section" action="actions-filter" method="post" id="filter-form">
                    <ul class="form__lst form__lst--app">
                        <div class="form__lst--flex">
                            <li class="form__itm">
                                <label for="client-filter">Sélectionner un client</label>
                                <select class="form__input form__input--select" type="date" name="client-filter" id="client-filter" required>
                                    ' . getCompaniesAsHTMLOptions($companies) . '
                                </select>
                            </li>
                            <li class="form__itm">
                                <label for="target-filter">Objectifs de la campagne (optionnel)</label>
                                <select class="form__input form__input--select" type="date" name="target-filter" id="target-filter">
                                    ' . getTargetsAsHTMLOptions($communicationObjectives) . '
                                </select>
                            </li>
                        </div>
                        <input type="submit" class="button button--filter" id="filter-button" aria-label="Filtrer les données entrées" value="Filtrer">
                        <input type="hidden" name="token" value="' . $_SESSION['token'] . '">
                        <input type="hidden" name="action" value="filter-campaigns">
                    </ul>
                </form>
                <form action="actions-filter" method="post" id="reinit-form">
                    <input type="submit" class="button button--reinit" id="filter-reinit" aria-label="Réinitialise tous les filtres" value="" title="Réinitialiser les filtres">
                    <input type="hidden" name="token" value="' . $_SESSION['token'] . '">
                    <input type="hidden" name="action" value="filter-reinit">
                </form>
        </div>';
}
