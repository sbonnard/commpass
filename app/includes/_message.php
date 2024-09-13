<?php

$messages = [
    'update_ok_pwd' => 'Mot de passe mis à jour !',
    'update_ok_email' => 'Email mis à jour!',
    'update_ok_phone' => 'Numéro de téléphone mis à jour!',
    'campaign_created_ok' => 'Nouvelle campagne créée.'
];

$errors = [
    'csrf' => 'Votre session est invalide.',
    'referer' => 'D\'où venez vous ?',
    'no_action' => 'Aucune action détectée.',
    'please_connect' => 'Vous devez être connecté pour accéder à cette page',
    'login_fail' => 'Les identifiants renseignés sont incorrects.',
    'update_ko_pwd' => 'Échec lors du changement de mot de passe.',
    'unmatched_pwd' => 'Les mots de passe ne correspondent pas.',
    'invalid_email' => 'L\'adresse email est invalide.',
    'update_ko_email' => 'Échec de la mise à jour de l\'email.',
    'invalid_phone' => 'Le numéro de téléphone est invalide.',
    'update_ko_phone' => 'Échec de la mise à jour du numéro de téléphone.',
    'campaign_name_ko' => 'Veuillez saisir un nom de campagne valide.',
    'campaign_company_ko' => 'Veuillez sélectionner une entreprise.',
    'campaign_interlocutor_ko' => 'Veuillez sélectionner un interlocuteur.',
    'budget_ko' => 'Veuillez saisir un budget valide, en chiffre uniquement et sans espaces.',
    'campaign_target_ko' => 'Veuillez sélectionner un objectif de campagne.',
    'campaign_created_ko' => 'Échec de la création de la campagne.',
    'date_ko' => 'Veuillez saisir une date valide.'
];


/**
 * Triggers if an error occurs and exits script.
 *
 * @param string $error The name of the error from errors array.
 * @return void
 */
function triggerError(string $error): void
{
    global $errors;
    $response = [
        'isOk' => false,
        'errorMessage' => $errors[$error]
    ];
    echo json_encode($response);
    exit;
}

/**
 * Add a new error message to display on next page. 
 *
 * @param string $errorMsg - Error message to display
 * @return void
 */
function addError(string $errors): void
{
    $_SESSION['error'] = $errors;
}


/**
 * Add a new message to display on next page. 
 *
 * @param string $message - Message to display
 * @return void
 */
function addMessage(string $message): void
{
    $_SESSION['msg'] = $message;
}

/**
 * Get error messages if the user fails to add a task.
 *
 * @return string The error message.
 */
function getErrorMessage(array $errors): string
{
    if (isset($_SESSION['error'])) {
        $e = ($_SESSION['error']);
        unset($_SESSION['error']);
        return '<p class="notif notif--error" id="error-message">' . $errors[$e] . '</p>';
    }
    return '';
}

/**
 * Get success messages if the user succeeds to add a task.
 *
 * @return string The success message.
 */
function getSuccessMessage(array $messages): string
{
    if (isset($_SESSION['msg'])) {
        $m = ($_SESSION['msg']);
        unset($_SESSION['msg']);
        return '<p class="notif notif--success" id="success-message">' . $messages[$m] . '</p>';
    }
    return '';
}
