<?php

/**
 * Get all operations from a campaign.
 *
 * @param PDO $dbCo - Database connection
 * @param array $get - Superglobal $_GET
 * @return array of operations or an empty array if the operation does not exist.
 */
function getAllOperationsFromACampaign(PDO $dbCo, array $get): array
{
    if (!isset($get['myo'])) {
        return [];
    }

    $query = $dbCo->prepare(
        'SELECT operation.id_operation, description, price, operation_date, id_campaign, id_company, id_media, operation.id_partner, id_brand
        FROM operation
            JOIN operation_brand ON operation_brand.id_operation = operation.id_operation
        WHERE operation.id_operation = :id_operation;'
    );

    $bindValues = [
        'id_operation' => intval($get['myo'])
    ];

    $query->execute($bindValues);

    $operation = $query->fetch(PDO::FETCH_ASSOC);

    return $operation;
}


/**
 * Deletes a operation from a campaign.
 *
 * @param $dbCo - Database connection
 * @return void
 */
function deleteOperation(PDO $dbCo, $inputData)
{
    global $errors;
    if (!empty($_REQUEST)) {
        $errors = [];

        if (empty($inputData['id'])) {
            $errors[] = 'ID de l\'opération manquant.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return json_encode(['success' => false, 'errors' => $errors]);
        }

        try {
            $dbCo->beginTransaction();

            $deleteFromOperationBrand = $dbCo->prepare("DELETE FROM operation_brand WHERE id_operation = :id;");

            $deleteFromOperation = $dbCo->prepare("DELETE FROM operation WHERE id_operation = :id;");

            $bindValues = [
                'id' => htmlspecialchars($inputData['id']),
            ];

            $isDeleteOk = $deleteFromOperationBrand->execute($bindValues) && $deleteFromOperation->execute($bindValues);

            $dbCo->commit();

            if ($isDeleteOk) {
                addMessage('delete_operation_ok');
                return json_encode(['success' => $isDeleteOk]);
            } else {
                addError('delete_operation_ko');
            }
        } catch (Exception $error) {
            addError('delete_operation_ko');
            $_SESSION['errors'] = "Erreur lors de la suppression : " . $error->getMessage();
            $dbCo->rollBack();

            return json_encode(['success' => false, 'errors' => $error->getMessage()]);
        }
    }
}
