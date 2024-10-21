<?php

require_once './includes/_database.php';
require_once './includes/_functions.php';
require_once './includes/_message.php';

header('Content-Type: application/json');


if (isset($_POST['id_company'])) {

    // Si une entreprise est définie dans le formulaire de création de campagne,
    // récupération des membres de l'entreprise en question avec une requête vers le serveur
    // avec $_POST['id_company'].

    $idCompany = intval($_POST['id_company']);
    
    $query = $dbCo->prepare(
        'SELECT id_user, firstname, lastname
        FROM users
        WHERE id_company = :idCompany AND enabled = 1
        ORDER BY firstname;'
    );

    // Exécution des valeurs liées récupérées dans le POST.
    $query->execute(['idCompany' => $idCompany]);
    
    // Récupération des données sous forme de tableau associatif.
    $users = $query->fetchAll(PDO::FETCH_ASSOC);

    // Encodage en json pour JavaScript (AJAX).
    echo json_encode($users);
    
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    
    $inputData = json_decode(file_get_contents('php://input'), true);

    error_log("Données reçues : " . print_r($inputData, true));

    if (isset($inputData['id'])) {
        // Récupérer l'id de l'opération à supprimer. 
        $operationId = $inputData['id'];
        error_log("ID d'opération : " . $operationId);

        global $errors;
        $errors = [];

        // Vérification des données reçues. 
        if (empty($operationId)) {
            $errors[] = 'ID de l\'opération manquant.';
        }

        if (!empty($errors)) {
            echo json_encode(['success' => false, 'errors' => $errors]);
            exit;
        }

        try {
            // Début d'une transaction pour supprimer à la fois les marques de operation_brand et les opérations. 
            $dbCo->beginTransaction();

            // Supprimer d'abord de operation_brand
            $deleteFromOperationBrand = $dbCo->prepare("DELETE FROM operation_brand WHERE id_operation = :id;");
            $isDeleteOk = $deleteFromOperationBrand->execute(['id' => intval($operationId)]);

            error_log("Suppression de operation_brand réussie : " . ($isDeleteOk ? 'oui' : 'non')); // Log pour vérifier la suppression

            if ($isDeleteOk) {
                // Ensuite, supprimer de operation
                $deleteFromOperation = $dbCo->prepare("DELETE FROM operation WHERE id_operation = :id;");
                $deleteFromOperation->execute(['id' => intval($operationId)]);

                // Si tout est OK, on commit le delete en base de données.
                $dbCo->commit();

                // On encode en json afin de rendre l'action lisible par JavaScript.
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression.']);
            }
        } catch (Exception $error) {
            // Si il y a une exception ou une erreur, on effectue un rollback() pour annuler les modifications de la base. 
            $dbCo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Erreur : ' . $error->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de l\'opération manquant.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
