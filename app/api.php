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
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $inputData = json_decode(file_get_contents('php://input'), true);
    // parse_str(file_get_contents("php://input"), $inputData);

    error_log("Données reçues : " . print_r($inputData, true));

    if (isset($inputData['id'])) {
        $operationId = $inputData['id'];
        error_log("ID d'opération : " . $operationId);

        global $errors;
        $errors = [];

        var_dump($operationId, empty($operationId));

        if (empty($operationId)) {
            $errors[] = 'ID de l\'opération manquant.';
        }

        if (!empty($errors)) {
            echo json_encode(['success' => false, 'errors' => $errors]);
            exit;
        }

        try {
            $dbCo->beginTransaction();

            // Supprimer d'abord de operation_brand
            $deleteFromOperationBrand = $dbCo->prepare("DELETE FROM operation_brand WHERE id_operation = :id;");
            $isDeleteOk = $deleteFromOperationBrand->execute(['id' => intval($operationId)]);

            error_log("Suppression de operation_brand réussie : " . ($isDeleteOk ? 'oui' : 'non')); // Log pour vérifier la suppression

            if ($isDeleteOk) {
                // Ensuite, supprimer de operation
                $deleteFromOperation = $dbCo->prepare("DELETE FROM operation WHERE id_operation = :id;");
                $deleteFromOperation->execute(['id' => intval($operationId)]);

                $dbCo->commit();
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression.']);
            }
        } catch (Exception $error) {
            $dbCo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Erreur : ' . $error->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de l\'opération manquant.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
