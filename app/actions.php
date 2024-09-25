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

if ($_POST['action'] === 'modify-pwd') {
    if (!isset($_POST['password']) || !isset($_POST['password-confirm']) || $_POST['password'] !== $_POST['password-confirm']) {
        $_SESSION['error'] = "unmatched_pwd";

        redirectTo('profil.php');
        exit;
    }

    $queryPWD = $dbCo->prepare('UPDATE users SET password = :password WHERE id_user = :id_user;');

    $bindValues = [
        'password' => password_hash(strip_tags($_POST['password']), PASSWORD_BCRYPT),
        'id_user' => strip_tags($_SESSION['id_user']),
    ];

    $isUpdateOk = $queryPWD->execute($bindValues);

    if ($isUpdateOk) {
        $_SESSION['msg'] = "update_ok_pwd";
    } else {
        $_SESSION['error'] = "update_ko_pwd";
    }
} else if ($_POST['action'] === 'modify-email') {
    if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "invalid_email";
        redirectTo('profil.php');
        exit;
    }

    $queryEmail = $dbCo->prepare('UPDATE users SET email = :email WHERE id_user = :id_user;');

    $bindValues = [
        'email' => strip_tags($_POST['email']),
        'id_user' => strip_tags($_SESSION['id_user']),
    ];

    $isUpdateOk = $queryEmail->execute($bindValues);

    if ($isUpdateOk) {
        $_SESSION['msg'] = "update_ok_email";
        redirectTo('profil.php');
        exit;
    } else {
        $_SESSION['error'] = "update_ko_email";
        redirectTo('profil.php');
        exit;
    }
} else if ($_POST['action'] === 'modify-phone') {
    if (!isset($_POST['phone']) || !preg_match('/^[0-9]{10}$/', $_POST['phone'])) {
        $_SESSION['error'] = "invalid_phone";

        redirectTo('profil.php');
        exit;
    }

    $queryPhone = $dbCo->prepare('UPDATE users SET phone = :phone WHERE id_user = :id_user;');

    $bindValues = [
        'phone' => strip_tags($_POST['phone']),
        'id_user' => strip_tags($_SESSION['id_user']),
    ];

    $isUpdateOk = $queryPhone->execute($bindValues);

    if ($isUpdateOk) {
        $_SESSION['msg'] = "update_ok_phone";
        redirectTo('profil.php');
        exit;
    } else {
        $_SESSION['error'] = "update_ko_phone";
        redirectTo('profil.php');
        exit;
    }
} else if ($_POST['action'] === 'create-campaign') {
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
        'budget' => strip_tags($_POST['budget']),
        'id_target' => intval($_POST['campaign_target']),
        'date' => strip_tags($_POST['date']),
        'id_user_TDC' => intval($_SESSION['id_user'])
    ];

    $isInsertOk = $queryCampaign->execute($bindValues);

    if ($isInsertOk) {
        addMessage('campaign_created_ok');
        redirectTo('dashboard.php');
    } else {
        addError('campaign_creation_ko');
    }
} else if ($_POST['action'] === 'create-operation') {

    checkOperationFormDatas();

    try {
        $dbCo->beginTransaction();

        $queryOperation = $dbCo->prepare(
            'INSERT INTO operation (description, price, date_, id_campaign, id_company, id_media, id_partner)  
        VALUES (:description, :price, :date, :id_campaign, :id_company, :id_media, :id_partner);'
        );

        $operationBindValues = [
            'description' => strip_tags($_POST['operation_description']),
            'price' => floatval($_POST['operation_amount']),
            'date' => strip_tags($_POST['date']),
            'id_campaign' => intval($_POST['id_campaign']),
            'id_company' => intval($_POST['id_company']),
            'id_media' => intval($_POST['operation_media']),
            'id_partner' => intval($_POST['operation_partner'])
        ];

        $isInsertOk = $queryOperation->execute($operationBindValues);

        $operationId = $dbCo->lastInsertId();

        if ($isInsertOk) {
            $queryBrand = $dbCo->prepare(
                'INSERT INTO operation_brand (id_operation, id_brand) VALUES (:id_operation, :id_brand);'
            );

            $brandBindValues = [
                'id_operation' => $operationId,
                'id_brand' => $_POST['operation_brand']
            ];

            $isBrandInsertOk = $queryBrand->execute($brandBindValues);

            if ($isBrandInsertOk) {
                $dbCo->commit();

                addMessage('operation_created_ok');

                redirectTo('campaign.php?myc=' . $_POST['id_campaign']);
            } else {
                $dbCo->rollBack();
                addError('operation_creation_ko');
                redirectTo('campaign.php?myc=' . $_POST['id_campaign']);
            }
        }
    } catch (PDOException $e) {
        $dbCo->rollBack();
        addError('operation_creation_ko');
    }
} elseif ($_POST['action'] === 'edit-operation') {

    if (!isset($_POST['id_campaign']) || empty($_POST['id_campaign']) || !is_numeric($_POST['id_campaign'])) {
        addError('campaign_id_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['id_operation']) || empty($_POST['id_operation']) || !is_numeric($_POST['id_operation'])) {
        addError('operation_id_ko');
        redirectTo();
        exit;
    }

    checkOperationFormDatas();


    try {
        $dbCo->beginTransaction();

        $queryOperation = $dbCo->prepare(
            'UPDATE operation 
            SET description = :description, price = :price, date_ = :date, id_media = :id_media, id_partner = :id_partner
            WHERE id_operation = :id_operation;'
        );

        $operationBindValues = [
            'description' => strip_tags($_POST['operation_description']),
            'price' => floatval($_POST['operation_amount']),
            'date' => strip_tags($_POST['date']),
            'id_operation' => intval($_POST['id_operation']),
            'id_media' => intval($_POST['operation_media']),
            'id_partner' => intval($_POST['operation_partner'])
        ];

        $isUpdateOk = $queryOperation->execute($operationBindValues);

        if ($isUpdateOk) {
            $queryBrand = $dbCo->prepare(
                'UPDATE operation_brand 
                SET id_brand = :id_brand WHERE id_operation = :id_operation;'
            );

            $brandBindValues = [
                'id_brand' => intval($_POST['operation_brand']),
                'id_operation' => intval($_POST['id_operation'])
            ];

            $isBrandUpdateOk = $queryBrand->execute($brandBindValues);

            if (!$isBrandUpdateOk) {
                $errorInfo = $queryBrand->errorInfo();
                var_dump($errorInfo);
            }

            if ($isBrandUpdateOk) {
                $dbCo->commit();

                addMessage('operation_update_ok');

                redirectTo('campaign.php?myc=' . $_POST['id_campaign']);
            } else {
                $dbCo->rollBack();
                addError('operation_update_ko');
                redirectTo('campaign.php?myc=' . $_POST['id_campaign']);
            }
        }
    } catch (PDOException $e) {
        $dbCo->rollBack();
        addError('operation_update_ko');
    }
} else if ($_POST['action'] === 'modify-colour') {
    if (!isset($_POST['profile_brand'])) {
        addError('brand_ko');
        redirectTo('profil.php');
        exit;
    }

    if (!isset($_POST['color'])) {
        addError('colour_ko');
        redirectTo('profil.php');
        exit;
    }

    $color = strip_tags($_POST['color']);

    if (preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {

        $queryBrandColor = $dbCo->prepare('
            UPDATE brand SET legend_colour_hex = :color
            WHERE id_brand = :id_brand;
        ');


        $bindValues = [
            'color' => $color,
            'id_brand' => intval($_POST['profile_brand'])
        ];

        $isUpdateOk = $queryBrandColor->execute($bindValues);

        if ($isUpdateOk) {
            addMessage('update_ok_colour');
            redirectTo('profil.php');
            exit;
        } else {
            addError('update_ko_colour');
            redirectTo('profil.php');
            exit;
        }
    } else {
        // Si la couleur n'est pas valide
        addError('invalid_colour_format');
        redirectTo('profil.php');
        exit;
    }
} else if ($_POST['action'] === 'filter-campaigns') {
    if (!isset($_POST['client-filter']) || empty($_POST['client-filter'])) {
        addError('no_client');
        redirectTo('dashboard.php');
        exit;
    }

    $_SESSION['filter']['id_company'] = intval($_POST['client-filter']);

    if (
        isset($_POST['filter']['target-filter']) && intval($_POST['filter']['target-filter'])
        && intval($_POST['filter']['target-filter']) >= 1 && intval($_POST['filter']['target-filter']) <= 3
    ) {
        $_SESSION['filter']['target-filter'] = intval($_POST['filter']['target-filter']);
    }
} else if (isset($_POST['action']) && $_POST['action'] === 'filter-reinit') {
    unset($_SESSION['filter']);
} else if ($_POST['action'] === 'set_annual_budget') {
    if (!isset($_POST['budget']) || empty($_POST['budget']) || !is_numeric($_POST['budget'])) {
        addError('budget_ko');
        redirectTo();
        exit;
    }

    $queryBudget = $dbCo->prepare('
        UPDATE company 
        SET annual_budget = :budget
        WHERE id_company = :id_company;
    ');

    $bindValues = [
        'budget' => floatval($_POST['budget']),
        'id_company' => $_SESSION['filter']['id_company']
    ];

    $isUpdateOk = $queryBudget->execute($bindValues);

    if ($isUpdateOk) {
        addMessage('budget_update_ok');
        redirectTo('dashboard.php');
    } else {
        addError('budget_update_ko');
        redirectTo();
    }
} else if ($_POST['action'] === 'set_campaign_budget') {
    if (!isset($_POST['budget']) || empty($_POST['budget']) || !is_numeric($_POST['budget'])) {
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
        'id_campaign' => $_POST['myc']
    ];

    $isUpdateOk = $queryBudget->execute($bindValues);

    if ($isUpdateOk) {
        addMessage('budget_update_ok');
        redirectTo('dashboard.php');
    } else {
        addError('budget_update_ko');
        redirectTo();
    }
}

redirectTo();
