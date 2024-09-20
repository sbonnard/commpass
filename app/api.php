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

if (isset($_GET['action']) && $_GET['action'] === 'delete_op') {
    // Vérification que l'ID de l'opération est bien passé dans l'URL
    if (isset($_GET['operation'])) {
        $operationId = $_GET['operation'];

        global $errors;
        $errors = [];

        // Validation de l'ID de l'opération
        if (empty($operationId)) {
            $errors[] = 'ID de l\'opération manquant.';
        }

        if (!empty($errors)) {
            echo json_encode(['success' => false, 'errors' => $errors]);
            exit;
        }

        try {
            $dbCo->beginTransaction();

            // DELETE FROM operation_brand
            $deleteFromOperationBrand = $dbCo->prepare("DELETE FROM operation_brand WHERE id_operation = :id;");
            // DELETE FROM operation
            $deleteFromOperation = $dbCo->prepare("DELETE FROM operation WHERE id_operation = :id;");

            // Liaison des valeurs
            $bindValues = [
                'id' => htmlspecialchars($operationId),
            ];

            // Exécution des requêtes
            $isDeleteOk = $deleteFromOperationBrand->execute($bindValues) && $deleteFromOperation->execute($bindValues);

            // Validation de la transaction
            $dbCo->commit();

            if ($isDeleteOk) {
                echo json_encode(['success' => true]);
                return;
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression.']);
                return;
            }
        } catch (Exception $error) {
            $dbCo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Erreur : ' . $error->getMessage()]);
            return;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de l\'opération manquant.']);
        return;
    }
}
