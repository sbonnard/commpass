<?php

/**
 * Fetch all user's datas from database.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $session - Superglobal $_SESSION.
 * @return array|boolean - Returns array of user's datas or false on failure.
 */
function fetchUserDatas(PDO $dbCo, array $session): array|bool
{
    // Vérifier si la clé 'id_user' existe dans $session
    if (!isset($session['id_user'])) {
        return false; // Ou une autre gestion d'erreur
    }

    $query = $dbCo->prepare(
        'SELECT id_user, username, firstname, lastname, email, phone, client, boss, id_company
        FROM users
        WHERE id_user = :id;'
    );

    // Préparer les valeurs pour la requête
    $bindValues = [
        'id' => intval($session['id_user'])
    ];

    // Exécuter la requête
    $query->execute($bindValues);

    // Récupérer les données utilisateur
    $userData = $query->fetch(PDO::FETCH_ASSOC);

    return $userData;
}


/**
 * Fetch all users from the database.
 *
 * @param PDO $dbCo - Connection to database.
 * @return array - User datas.
 */
function fetchAllUsers(PDO $dbCo): array
{
    $query = $dbCo->prepare(
        'SELECT id_user, username, firstname, lastname, email, phone, client, boss, id_company, enabled
        FROM users
        ORDER BY boss DESC, enabled DESC, firstname ASC;'
    );

    $query->execute();

    $userData = $query->fetchAll();

    return $userData;
}


/**
 * Fetch all users that are not clients from your company.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $session - Superglobale $_SESSION to check id_company.
 * @return array - An array containing all non client users from your company.
 */
function fetchNonClientUsers(PDO $dbCo, array $session): array
{
    $nonClientUsers = [];

    if (isset($session['client']) && $session['client'] === 0) {
        $query = $dbCo->prepare(
            'SELECT id_user, firstname, lastname, client, boss, id_company
            FROM users
            WHERE id_company = :idcompany;'
        );

        $bindValues = [
            'idcompany' => intval($session['id_company'])
        ];

        $query->execute($bindValues);

        $nonClientUsers = $query->fetchAll(PDO::FETCH_ASSOC);
    }

    return $nonClientUsers;
}


/**
 * Fetch all intyerlocutors from a company if filter is set.
 *
 * @param PDO $dbCo - PDO connection
 * @param array $session - Superglobal Session
 * @return array - Array of interlocutors
 */
function fetchInterlocutors(PDO $dbCo, array $session): array
{
    $query = $dbCo->prepare(
        'SELECT id_user, firstname, lastname
        FROM users
        WHERE id_company = :id_company;'
    );

    $bindValues = [
        'id_company' => intval($session['filter']['id_company'])
    ];

    $query->execute($bindValues);

    return $query->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Get interlocutors has option if company is defined in session filters.
 *
 * @param array $interlocutors - Interlocutors array.
 * @return string - Interlocutors as HTML options.
 */
function getInterlocutorsAsOptions(array $interlocutors): string
{
    $options = '';
    foreach ($interlocutors as $interlocutor) {
        $options .= '<option value="' . $interlocutor['id_user'] . '">' . $interlocutor['firstname'] . ' ' . $interlocutor['lastname'] . '</option>';
    }
    return $options;
}
