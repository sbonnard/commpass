<?php
session_start();

//CONFIG AND CONNECTION
require_once "includes/_config.php";
require_once "includes/_database.php";

// FUNCTIONS
require_once "includes/_functions.php";
require_once "includes/_security.php";
require_once "includes/_message.php";

// CLASSES

require_once "includes/classes/class.company.php";

// header('Content-type:application/json');


if (!isset($_REQUEST['action'])) {
    redirectTo('dashboard');
    exit;
}

// Check CSRF
preventFromCSRF();

if ($_POST['action'] === 'create-campaign') {

    $_SESSION['form_data'] = [
        'name' => strip_tags($_POST['campaign_name']),
        'id_company' => intval($_POST['campaign_company']),
        'id_interlocutor' => intval($_POST['campaign_interlocutor']),
        'budget' => floatval($_POST['budget']),
        'id_target' => intval($_POST['campaign_target']),
        'date_start' => strip_tags($_POST['date_start']),
        'date_end' => strip_tags($_POST['date_end']),
        'id_user_TDC' => intval($_POST['user_TDC'])
    ];

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

    if (!isset($_POST['date_start']) || empty($_POST['date_start']) || !isset($_POST['date_end']) || empty($_POST['date_end'])) {
        addError('date_ko');
        redirectTo();
        exit;
    }

    $queryCampaign = $dbCo->prepare(
        'INSERT INTO campaign (campaign_name, id_company, id_user, budget, id_target, date_start, date_end, id_user_TDC)  
        VALUES (:name, :id_company, :id_interlocutor, :budget, :id_target, :date_start, :date_end, :id_user_TDC);'
    );

    $bindValues = [
        'name' => strip_tags($_POST['campaign_name']),
        'id_company' => intval($_POST['campaign_company']),
        'id_interlocutor' => intval($_POST['campaign_interlocutor']),
        'budget' => floatval($_POST['budget']),
        'id_target' => intval($_POST['campaign_target']),
        'date_start' => strip_tags($_POST['date_start']),
        'date_end' => strip_tags($_POST['date_end']),
        'id_user_TDC' => intval($_POST['user_TDC'])
    ];

    $isInsertOk = $queryCampaign->execute($bindValues);

    $lastInsertID = $dbCo->lastInsertId();

    if ($isInsertOk) {
        unset($_SESSION['form_data']);
        $_SESSION['filter']['id_company'] = intval($_POST['campaign_company']);
        addMessage('campaign_created_ok');
        redirectTo('campaign?myc=' . $lastInsertID);
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

    if (!isset($_POST['date_start']) || empty($_POST['date_start'])) {
        addError('date_ko');
        redirectTo();
        exit;
    }

    $queryUpdateCampaign = $dbCo->prepare(
        'UPDATE campaign 
        SET campaign_name = :campaign_name,
            budget = :budget,
            date_start = :date_start,
            date_end = :date_end,
            id_user = :user, 
            id_company = :company, 
            id_target = :target,
            id_user_TDC = :userTDC
        WHERE id_campaign = :id_campaign;'
    );

    $bindValues = [
        'campaign_name' => strip_tags($_POST['campaign_name']),
        'budget' => floatval($_POST['budget']),
        'date_start' => strip_tags($_POST['date_start']),
        'date_end' => strip_tags($_POST['date_end']),
        'user' => intval($_POST['campaign_interlocutor']),
        'company' => intval($_POST['campaign_company']),
        'target' => intval($_POST['campaign_target']),
        'userTDC' => intval($_POST['user_TDC']),
        'id_campaign' => intval($_POST['id_campaign'])
    ];

    $isUpdateOk = $queryUpdateCampaign->execute($bindValues);

    if ($isUpdateOk) {
        addMessage('campaign_updated_ok');
        redirectTo('campaign?myc=' . $_POST['id_campaign'] . '&client=' . $_POST['campaign_company']);
    } else {
        addError('campaign_update_ko');
    }
} elseif ($_POST['action'] === 'delete-campaign') {
    try {
        $dbCo->beginTransaction();

        //Prépare la suppression d'une campagne.
        $queryDeleteCampaign = $dbCo->prepare('DELETE FROM campaign WHERE id_campaign = :id_campaign;');
        $bindValues = [
            'id_campaign' => intval($_POST['id_campaign'])
        ];
        $isDeleteOk = $queryDeleteCampaign->execute($bindValues);

        if (!$isDeleteOk) {
            throw new Exception('Erreur lors de la suppression de la campagne');
        }

        // Une campagne dépendant de ses opérations, je supprime les opérations afin qu'elles ne restent pas en BDD 
        $querySelectOperations = $dbCo->prepare('SELECT id_operation FROM operation WHERE id_campaign = :id_campaign;');
        $querySelectOperations->execute($bindValues);
        $operations = $querySelectOperations->fetchAll(PDO::FETCH_ASSOC);

        // Une opération étant associée à une ou plusieurs marques, je dois supprimer les marques associées.
        foreach ($operations as $operation) {
            $queryDeleteBrands = $dbCo->prepare('DELETE FROM operation_brand WHERE id_operation = :id_operation;');
            $isDeleteBrandOk = $queryDeleteBrands->execute(['id_operation' => $operation['id_operation']]);

            if (!$isDeleteBrandOk) {
                throw new Exception('Erreur lors de la suppression des marques');
            }
        }

        $queryDeleteOperation = $dbCo->prepare('DELETE FROM operation WHERE id_campaign = :id_campaign;');
        $isDeleteOperationOk = $queryDeleteOperation->execute($bindValues);

        if ($isDeleteOk && $isDeleteOperationOk) {
            $dbCo->commit();
            addMessage('campaign_deleted_ok');
            redirectTo('my-client?client=' . $_SESSION['filter']['id_company']);
        } else {
            throw new Exception('Erreur lors de la suppression des opérations');
        }
    } catch (Exception $e) {
        $dbCo->rollBack();
        addError('campaign_deletion_ko');
        redirectTo();
    }
} else if ($_POST['action'] === 'set_campaign_budget') {
    if (!isset($_POST['budget']) || !is_numeric($_POST['budget'])) {
        addError('budget_ko');
        redirectTo();
        exit;
    }

    $queryBudget = $dbCo->prepare('
        UPDATE campaign 
        SET budget = :budget
        WHERE id_campaign = :id_campaign;
    ');

    $bindValues = [
        'budget' => floatval($_POST['budget']),
        'id_campaign' => intval($_POST['myc'])
    ];

    $isUpdateOk = $queryBudget->execute($bindValues);

    if ($isUpdateOk) {
        addMessage('budget_update_ok');
        redirectTo('campaign?myc=' . $_POST['myc']);
    } else {
        addError('budget_update_ko');
        redirectTo();
    }
} else if ($_POST['action'] === 'set_annual_budget') {
    if (!isset($_POST['budget']) || !is_numeric($_POST['budget'])) {
        addError('budget_ko');
        redirectTo();
        exit;
    }

    if (checkNewYearBudgetLink($dbCo, $_SESSION) === false) {
        $queryBudget = $dbCo->prepare(
            'INSERT INTO budgets (year, annual_budget, id_company)
            VALUES (:year, :annual_budget, :id_company);'
        );

        $bindValues = [
            'year' => date('Y'),
            'annual_budget' => floatval($_POST['budget']),
            'id_company' => intval($_SESSION['filter']['id_company'])
        ];

        $isInsertOk = $queryBudget->execute($bindValues);

        if ($isInsertOk) {
            addMessage('budget_update_ok');
            redirectTo('my-client?client=' . $_SESSION['filter']['id_company']);
        } else {
            addError('budget_update_ko');
            redirectTo();
        }
    } else {
        $queryBudget = $dbCo->prepare('
                UPDATE budgets 
                SET annual_budget = :budget
                WHERE id_company = :id_company AND year = YEAR(CURDATE());
            ');

        $bindValues = [
            'budget' => floatval($_POST['budget']),
            'id_company' => $_SESSION['filter']['id_company']
        ];

        $isUpdateOk = $queryBudget->execute($bindValues);

        if ($isUpdateOk) {
            addMessage('budget_update_ok');
            redirectTo('my-client?client=' . $_SESSION['filter']['id_company']);
        } else {
            addError('budget_update_ko');
            redirectTo();
        }
    }
}

redirectTo();
