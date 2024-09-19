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
        'SELECT *
        FROM operation
        WHERE id_operation = :id_operation;'
    );

    $bindValues = [
        'id_operation' => intval($get['myo'])
    ];

    $query->execute($bindValues);

    $operation = $query->fetch(PDO::FETCH_ASSOC);

    return $operation;
}
