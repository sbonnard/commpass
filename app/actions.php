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
    }

    if (!isset($_POST['campaign_company']) || empty($_POST['campaign_company'])) {
        addError('campaign_company_ko');
    }

    if (!isset($_POST['campaign_interlocutor']) || empty($_POST['campaign_interlocutor'])) {
        addError('campaign_interlocutor_ko');
    }

    if (!isset($_POST['budget']) || empty($_POST['budget']) || !is_numeric($_POST['budget'])) {
        addError('budget_ko');
    }

    if (!isset($_POST['campaign_target']) || empty($_POST['campaign_target'])) {
        addError('campaign_target_ko');
    }

    $queryCampaign = $dbCo->prepare(
        'INSERT INTO campaign (campaign_name, id_company, id_user, budget, id_target) 
        VALUES (:name, :id_company, :id_interlocutor, :budget, :id_target);'
    );

    $bindValues = [
        'name' => strip_tags($_POST['campaign_name']),
        'id_company' => intval($_POST['campaign_company']),
        'id_interlocutor' => intval($_POST['campaign_interlocutor']),
        'budget' => strip_tags($_POST['budget']),
        'id_target' => intval($_POST['campaign_target'])
    ];

    $isInsertOk = $queryCampaign->execute($bindValues);

    if ($isInsertOk) {
        addMessage('campaign_created_ok');
    } else {
        addError('campaign_creation_ko');
    }
} else if ($_POST['action'] === 'create-operation') {

    var_dump("action = create-operation");

    if (!isset($_POST['operation_description']) || strlen($_POST['operation_description']) > 255) {
        addError('operation_description_ko');
        redirectTo();
        exit;
    }

    var_dump("description ok");

    if (!isset($_POST['operation_amount']) || empty($_POST['operation_amount']) || !is_numeric($_POST['operation_amount']) || $_POST['operation_amount'] < 0) {
        addError('operation_amount_ko');
        redirectTo();
        exit;
    }

    var_dump("price ok");

    if (!isset($_POST['date']) || empty($_POST['date'])) {
        addError('operation_date_ko');
        redirectTo();
        exit;
    }

    var_dump("date ok");

    if (!isset($_POST['operation_brand']) || $_POST['operation_brand'] == null) {
        addError('operation_brand_ko');
        redirectTo();
        exit;
    }

    var_dump("brand ok");

    try {
        $dbCo->beginTransaction();

        $queryOperation = $dbCo->prepare(
            'INSERT INTO operation (description, price, date_, id_campaign, id_company) 
        VALUES (:description, :price, :date, :id_campaign, :id_company);'
        );

        var_dump("first query ok");

        $operationBindValues = [
            'description' => strip_tags($_POST['operation_description']),
            'price' => floatval($_POST['operation_amount']),
            'date' => strip_tags($_POST['date']),
            'id_campaign' => strip_tags($_POST['id_campaign']),
            'id_company' => strip_tags($_POST['id_company'])
        ];

        var_dump($operationBindValues);

        $isInsertOk = $queryOperation->execute($operationBindValues);

        var_dump('operation execute ok');

        $operationId = $dbCo->lastInsertId();

        var_dump($operationId);

        if ($isInsertOk) {
            $queryBrand = $dbCo->prepare(
                'INSERT INTO operation_brand (id_operation, id_brand) VALUES (:id_operation, :id_brand);'
            );

            var_dump("second query ok");

            $brandBindValues = [
                'id_operation' => $operationId,
                'id_brand' => $_POST['operation_brand']
            ];

        var_dump($brandBindValues);

            $isBrandInsertOk = $queryBrand->execute($brandBindValues);

        var_dump($isBrandInsertOk);

            if ($isBrandInsertOk) {
                $dbCo->commit();

        var_dump('commit ok');

                addMessage('operation_created_ok');

        var_dump('message ok');

                // redirectTo('campaign.php?myc=' . $_GET['myc']);
            } else {
                $dbCo->rollBack();
                addError('operation_creation_ko');
                // redirectTo('campaign.php?myc=' . $_GET['myc']);
            }
        }
    } catch (PDOException $e) {
        $dbCo->rollBack();
        addError('operation_creation_ko');
    }
}

// redirectTo('dashboard.php');
