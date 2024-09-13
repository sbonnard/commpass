<?php

require_once './includes/_database.php'; 
require_once './includes/_functions.php';
require_once './includes/_message.php';

header('Content-Type: application/json');

if (isset($_POST['id_company'])) {
    $idCompany = $_POST['id_company'];


    $query = $dbCo->prepare(
        'SELECT id_user, firstname, lastname
        FROM users
        WHERE id_company = :idCompany
        ORDER BY firstname;'
    );
    $query->execute(['idCompany' => $idCompany]);

    $users = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
}