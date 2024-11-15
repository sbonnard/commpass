<?php

$messages = [
    'update_ok_pwd' => 'Mot de passe mis à jour !',
    'update_ok_email' => 'Email mis à jour!',
    'update_ok_phone' => 'Numéro de téléphone mis à jour!',
    'campaign_created_ok' => 'Nouvelle campagne créée.',
    'operation_created_ok' => 'Opération créée avec succès.',
    'operation_update_ok' => 'Opération mise à jour avec succès.',
    'delete_operation_ok' => 'Opération supprimée avec succès.',
    'update_ok_colour' => 'La couleur de la marque a été mise à jour avec succès.',
    'budget_update_ok' => 'Le budget annuel a été mis à jour avec succès.',
    'new_client_created_ok' => 'Nouveau client créé avec succès.',
    'new_user_created_ok' => 'Nouvel utilisateur créé avec succès.',
    'partner_created_ok' => 'Nouveau partenaire créé avec succès.',
    'brand_created_ok' => 'Nouvelle marque créée.',
    'campaign_updated_ok' => 'La campagne a été mise à jour',
    'campaign_deleted_ok' => 'La campagne a été supprimée.',
    'client_disabled_ok' => 'Le compte utilisateur a été désactivé',
    'client_enabled_ok' => 'Le compte utilisateur a été réactivé'
];

$errors = [
    'database_error' => 'Problème de connexion à la base de données.',
    'csrf' => 'Votre session est invalide.',
    'referer' => 'D\'où venez vous ?',
    'authorization_ko' => 'Vous n\'êtes pas autorisé à accéder à cette page.',
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
    'date_ko' => 'Veuillez saisir une date valide.',
    'operation_description_ko' => 'Veuillez saisir une description de l\'opération.',
    'operation_amount_ko' => 'Veuillez saisir un montant de l\'opération, en chiffre uniquement et sans espaces.',
    'operation_date_ko' => 'Veuillez saisir une date valide pour l\'opération.',
    'operation_creation_ko' => 'Échec de la création de l\'opération.',
    'campaign_id_ko' => 'Veuillez sélectionner une campagne valide.',
    'operation_id_ko' => 'Veuillez sélectionner une opération valide.',
    'operation_update_ko' => 'Erreur lors de la mise à jour de l\'opération.',
    'delete_operation_ko' => 'Erreur lors de la suppression de l\'opération.',
    'brand_ko' => 'Veuillez saisir une marque valide.',
    'colour_ko' => 'Veuillez saisir une couleur valide.',
    'update_ko_colour' => 'Erreur lors de la mise à jour de la couleur de la marque.',
    'no_client' => 'Aucun client n\'a été trouvé.',
    'budget_update_ok' => 'Erreur lors de la mise à jour du budget annuel.',
    'company_name_ko' => 'Entrez un nom de société valide.',
    'new_client_creation_ko' => 'Erreur lors de la création du client.',
    'username_ko' => 'Entrez un nom d\'utilisateur valide.',
    'password_choice_ko' => 'Entrez un mot de passe d\'au moins 8 caractères.',
    'email_ko' => 'Entrez un email valide.',
    'firstname_ko' => 'Entrez un prénom valide.',
    'lastname_ko' => 'Entrez un nom de famille valide.',
    'company_ko' => 'Sélectionnez une entreprise.',
    'phone_ko' => 'Entrez un numéro de téléphone valide, sans espaces ni caractères spéciaux.',
    'status_ko' => 'Le formulaire n\'a pas correctement établi le statut de l\'utilisateur.',
    'new_user_creation_ko' => 'Erreur lors de la création de l\'utilisateur.',
    'partner_name_ko' => 'Entrez un nom de partenaire valide.',
    'partner_colour_ko' => 'Sélectionnez une couleur pour le partenaire',
    'partner_created_ko' => 'Échec lors de la création du partenaire.',
    'brand_name_ko' => 'Le nom de la marque est invalide.',
    'colour_ko' => 'Sélectionnez une couleur.',
    'company_id_ko' => 'Aucune entreprise sélectionnée.',
    'brand_creation_ko' => 'Échec lors de la création de la marque.',
    'campaign_update_ko' => 'Échec de la modification de la campagne',
    'campaign_deletion_ko' => 'Erreur lors de la suppression de la campagne.',
    'no_date_range' => 'Aucune date correspondante.',
    'year_ko' => 'L\'année n\'a pas pu être déterminée.',
    'client_ko' => 'Aucun client trouvé.',
    'client_disabled_ko' => 'Erreur lors de la désactivation de l\'utilisateur.',
    'client_enabled_ko' => 'Erreur lors de la réactivation de l\'utilisateur.',
    'add-media-empty' => 'Aucun média saisi.'
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
