<?php

require_once './includes/_database.php';
require_once './includes/_functions.php';
require_once './includes/_message.php';

header('Content-Type: application/json');

$inputData = json_decode(file_get_contents('php://input'), true);

if (!isset($inputData['action'])) {
    triggerError('no_action');
}

// CSRF prevention
preventFromCSRFAPI($inputData);

if (isset($_POST['filter-campaigns'])) {
    filterCampaigns($dbCo, $inputData);
}
