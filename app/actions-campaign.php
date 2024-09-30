<?php
session_start();

//CONFIG AND CONNECTION
require_once "includes/_config.php";
require_once "includes/_database.php";

// FUNCTIONS
require_once "includes/_functions.php";
require_once "includes/_security.php";
require_once "includes/_message.php";

// header('Content-type:application/json');


if (!isset($_REQUEST['action'])) {
    redirectTo('dashboard.php');
    exit;
}

// Check CSRF
preventFromCSRF();

if ($_POST['action'] === 'create-campaign') {
    if (!isset($_POST['campaign_name']) || empty($_POST['campaign_name'])) {
        addError('campaign_name_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['campaign_company']) || empty($_POST['campaign_company'])) {
        addError('campaign_company_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['campaign_interlocutor']) || empty($_POST['campaign_interlocutor'])) {
        addError('campaign_interlocutor_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['budget']) || !is_numeric($_POST['budget']) || $_POST['budget'] < 0) {
        addError('budget_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['campaign_target']) || empty($_POST['campaign_target'])) {
        addError('campaign_target_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['date']) || empty($_POST['date'])) {
        addError('date_ko');
        redirectTo();
        exit;
    }

    $queryCampaign = $dbCo->prepare(
        'INSERT INTO campaign (campaign_name, id_company, id_user, budget, id_target, date, id_user_TDC)  
        VALUES (:name, :id_company, :id_interlocutor, :budget, :id_target, :date, :id_user_TDC);'
    );

    $bindValues = [
        'name' => strip_tags($_POST['campaign_name']),
        'id_company' => intval($_POST['campaign_company']),
        'id_interlocutor' => intval($_POST['campaign_interlocutor']),
        'budget' => floatval($_POST['budget']),
        'id_target' => intval($_POST['campaign_target']),
        'date' => strip_tags($_POST['date']),
        'id_user_TDC' => intval($_POST['user_TDC'])
    ];

    $isInsertOk = $queryCampaign->execute($bindValues);

    if ($isInsertOk) {
        addMessage('campaign_created_ok');
        redirectTo('dashboard.php');
    } else {
        addError('campaign_creation_ko');
    }
} else if ($_POST['action'] === 'modify-campaign') {
    if (!isset($_POST['campaign_name']) || empty($_POST['campaign_name'])) {
        addError('campaign_name_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['campaign_company']) || empty($_POST['campaign_company'])) {
        addError('campaign_company_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['campaign_interlocutor']) || empty($_POST['campaign_interlocutor'])) {
        addError('campaign_interlocutor_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['budget']) || !is_numeric($_POST['budget']) || $_POST['budget'] < 0) {
        addError('budget_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['campaign_target']) || empty($_POST['campaign_target'])) {
        addError('campaign_target_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['date']) || empty($_POST['date'])) {
        addError('date_ko');
        redirectTo();
        exit;
    }

    $queryUpdateCampaign = $dbCo->prepare(
        'UPDATE campaign 
        SET campaign_name = :campaign_name,
            budget = :budget,
            date = :date,
            id_user = :user, 
            id_company = :company, 
            id_target = :target,
            id_user_TDC = :userTDC
        WHERE id_campaign = :id_campaign;'
    );
    
    $bindValues = [
        'campaign_name' => strip_tags($_POST['campaign_name']),
        'budget' => floatval($_POST['budget']),
        'date' => strip_tags($_POST['date']),
        'user' => intval($_POST['campaign_interlocutor']),
        'company' => intval($_POST['campaign_company']),
        'target' => intval($_POST['campaign_target']),
        'userTDC' => intval($_POST['user_TDC']),
        'id_campaign' => intval($_POST['id_campaign'])
    ];

    $isUpdateOk = $queryUpdateCampaign->execute($bindValues);

    if ($isUpdateOk) {
        addMessage('campaign_updated_ok');
        redirectTo('dashboard.php');
    } else {
        addError('campaign_update_ko');
    }

} else if ($_POST['action'] === 'delete-campaign') {
    
} 

redirectTo();