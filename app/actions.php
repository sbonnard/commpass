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
        addError('unmatched_pwd');
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
        addMessage('update_ok_pwd');
    } else {
        addError('update_ko_pwd');
    }
} else if ($_POST['action'] === 'modify-email') {
    if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        addError('invalid_email');
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
        addMessage('update_ok_email');
        redirectTo('profil.php');
        exit;
    } else {
        addError('update_ko_email');
        redirectTo('profil.php');
        exit;
    }
} else if ($_POST['action'] === 'modify-phone') {
    if (!isset($_POST['phone']) || !preg_match('/^[0-9]{10}$/', $_POST['phone'])) {
        addError('invalid_phone');
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
        addMessage('update_ok_phone');
        redirectTo('profil.php');
        exit;
    } else {
        addError('update_ko_phone');
        redirectTo('profil.php');
        exit;
    }
} else if ($_POST['action'] === 'create-operation') {

    checkOperationFormDatas();

    try {
        $dbCo->beginTransaction();

        $queryOperation = $dbCo->prepare(
            'INSERT INTO operation (description, price, operation_date, id_campaign, id_company, id_media, id_partner)  
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
            SET description = :description, price = :price, operation_date = :date, id_media = :id_media, id_partner = :id_partner
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
} else if ($_POST['action'] === 'new_brand') {
    if (!isset($_POST['brand_name']) || empty($_POST['brand_name']) || strlen($_POST['brand_name']) > 100) {
        addError('brand_name_ko');
        redirectTo('');
        exit;
    }

    if (!isset($_POST['color']) || empty($_POST['color']) || strlen($_POST['color']) > 7) {
        addError('colour_ko');
        redirectTo('');
        exit;
    }

    if (!isset($_POST['id_company']) || empty($_POST['id_company']) || !intval($_POST['id_company'])) {
        addError('company_id_ko');
        redirectTo('');
        exit;
    }

    $query = $dbCo->prepare(
        'INSERT INTO brand (brand_name, legend_colour_hex, id_company)
        VALUES (:brand_name, :color, :id_company);'
    );

    $bindValues = [
        'brand_name' => strip_tags($_POST['brand_name']),
        'color' => strip_tags($_POST['color']),
        'id_company' => intval($_POST['id_company'])
    ];

    $isInsertOk = $query->execute($bindValues);

    if ($isInsertOk) {
        addMessage('brand_created_ok');
        redirectTo('clients.php');
    } else {
        addError('brand_creation_ko');
        redirectTo('');
    }
} else if ($_POST['action'] === 'create_client') {
    if (!isset($_POST['company_name']) || empty($_POST['company_name']) || !is_string($_POST['company_name']) || strlen($_POST['company_name']) > 100) {
        addError('company_name_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['annual_budget']) || empty($_POST['annual_budget']) || !is_numeric($_POST['annual_budget'])) {
        addError('budget_ko');
        redirectTo();
        exit;
    }

    $queryNewClient = $dbCo->prepare(
        'INSERT INTO company (company_name, annual_budget)
        VALUES (:company_name, :budget);'
    );

    $bindValues = [
        'company_name' => htmlspecialchars($_POST['company_name']),
        'budget' => floatval($_POST['annual_budget'])
    ];

    $isInsertClientOk = $queryNewClient->execute($bindValues);

    if ($isInsertClientOk) {
        addMessage('new_client_created_ok');
        redirectTo('clients.php');
    } else {
        addError('new_client_creation_ko');
    }
} else if ($_POST['action'] === 'create_user') {
    if (!isset($_POST['username']) || empty($_POST['username']) || !is_string($_POST['username']) || strlen($_POST['username']) > 100) {
        addError('username_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['firstname']) || empty($_POST['firstname']) || !is_string($_POST['firstname']) || strlen($_POST['firstname']) > 50) {
        addError('firstname_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['lastname']) || empty($_POST['lastname']) || !is_string($_POST['lastname']) || strlen($_POST['lastname']) > 50) {
        addError('lastname_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['company']) && empty($_POST['company']) || !is_numeric($_POST['company'])) {
        addError('company_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['password']) || empty($_POST['password']) || !is_string($_POST['password']) || strlen($_POST['password']) < 8) {
        addError('password_choice_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['email']) || empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        addError('email_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['phone']) || empty($_POST['phone']) || strlen($_POST['phone']) > 10) {
        addError('phone_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['status']) || $_POST['status'] > 1) {
        addError('status_ko');
        redirectTo();
        exit;
    }

    $queryNewUser = $dbCo->prepare(
        'INSERT INTO users (username, firstname, lastname, email, phone, password, id_company, client, boss)
        VALUES (:username, :firstname, :lastname, :email, :phone, :password, :id_company, :status, :boss);'
    );

    $bindValues = [
        'username' => htmlspecialchars($_POST['username']),
        'firstname' => htmlspecialchars($_POST['firstname']),
        'lastname' => htmlspecialchars($_POST['lastname']),
        'email' => htmlspecialchars($_POST['email']),
        'phone' => htmlspecialchars($_POST['phone']),
        'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
        'id_company' => intval($_POST['company']),
        'status' => intval($_POST['status']),
        'boss' => intval($_POST['boss'])
    ];

    $isInsertUserOk = $queryNewUser->execute($bindValues);

    if ($isInsertUserOk) {
        addMessage('new_user_created_ok');
        redirectTo('clients.php');
    } else {
        addError('new_user_creation_ko');
    }
} else if ($_POST['action'] === 'create_partner') {
    if (!isset($_POST['partner_name']) || empty($_POST['partner_name']) || !is_string($_POST['partner_name']) || strlen($_POST['partner_name']) > 255) {
        addError('partner_name_ko');
        redirectTo();
        exit;
    }

    $queryNewPartner = $dbCo->prepare(
        'INSERT INTO partner (partner_name)
        VALUES (:partner_name);'
    );

    $bindValues = [
        'partner_name' => htmlspecialchars($_POST['partner_name'])
    ];

    $isInsertPartnerOk = $queryNewPartner->execute($bindValues);

    if ($isInsertPartnerOk) {
        addMessage('partner_created_ok');
        redirectTo();
    } else {
        addError('partner_created_ko');
    }
}

redirectTo();
