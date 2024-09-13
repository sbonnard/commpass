<?php

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
        'SELECT id_user, username, firstname, lastname, email, phone, client, boss, id_company
        FROM users;'
    );

    $query->execute();

    $userData = $query->fetchAll();

    return $userData;
}
