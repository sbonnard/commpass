<?php

require_once './includes/_database.php';
require_once './includes/_functions.php';
require_once './includes/_message.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Récupère le corps de la requête
    $input = json_decode(file_get_contents('php://input'), true);

    // Vérifie si l'ID est présent
    if (isset($input['id'])) {
        $id = $input['id'];

        // Prépare la requête de suppression
        $stmt = $dbCo->prepare("DELETE FROM operation WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Exécute la requête et vérifie le résultat
        try {
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Élément supprimé avec succès.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression: ' . $e->getMessage()]);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID non fourni.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
