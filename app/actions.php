<?php
session_start();

//CONFIG AND CONNECTION
require_once "includes/_config.php";
require_once "includes/_database.php";

// FUNCTIONS
require_once "includes/_functions.php";
require_once "includes/_security.php";
require_once "includes/_message.php";

if (!isset($_REQUEST['action'])) {
    redirectTo('dashboard');
    exit;
}

// Check CSRF
preventFromCSRF();

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($_POST['action'] === 'modify-pwd') { // Update password on profil.php.
    if (!isset($_POST['password']) || !isset($_POST['password-confirm']) || $_POST['password'] !== $_POST['password-confirm']) {
        addError('unmatched_pwd');
        redirectTo('profil');
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
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
else if ($_POST['action'] === 'modify-email') { // Update email on profil.php
    if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        addError('invalid_email');
        redirectTo('profil');
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
        redirectTo('profil');
        exit;
    } else {
        addError('update_ko_email');
        redirectTo('profil');
        exit;
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
else if ($_POST['action'] === 'modify-phone') { // update phone number on profil.php
    if (!isset($_POST['phone']) || !preg_match('/^[0-9]{10}$/', $_POST['phone'])) {
        addError('invalid_phone');
        redirectTo('profil');
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
        redirectTo('profil');
        exit;
    } else {
        addError('update_ko_phone');
        redirectTo('profil');
        exit;
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
else if ($_POST['action'] === 'create-operation') { // Create an operation on operation.php

    // Pour conserver les saisies dans les inputs en cas d'erreur de l'utilisateur 
    $_SESSION['form_data'] = [
        'description' => strip_tags($_POST['operation_description']),
        'price' => floatval($_POST['operation_amount']),
        'date' => strip_tags($_POST['date']),
        'id_campaign' => intval($_POST['id_campaign']),
        'id_company' => intval($_POST['id_company']),
        'id_media' => intval($_POST['operation_media']),
        'id_partner' => intval($_POST['operation_partner'])
    ];

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
                unset($_SESSION['form_data']);

                $dbCo->commit();

                addMessage('operation_created_ok');

                redirectTo('campaign?myc=' . $_POST['id_campaign']);
            } else {
                $dbCo->rollBack();
                addError('operation_creation_ko');
                redirectTo();
            }
        }
    } catch (PDOException $e) {
        $dbCo->rollBack();
        addError('operation_creation_ko');
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
else if ($_POST['action'] === 'edit-operation') { // Éditer une operation sur operation.php.

    // Pour conserver les saisies dans les inputs en cas d'erreur de l'utilisateur 
    $_SESSION['form_data'] = [
        'description' => strip_tags($_POST['operation_description']),
        'price' => floatval($_POST['operation_amount']),
        'date' => strip_tags($_POST['date']),
        'id_campaign' => intval($_POST['id_campaign']),
        'id_company' => intval($_POST['id_company']),
        'id_media' => intval($_POST['operation_media']),
        'id_partner' => intval($_POST['operation_partner'])
    ];

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
                unset($_SESSION['form_data']);

                $dbCo->commit();

                addMessage('operation_update_ok');

                redirectTo('campaign?myc=' . $_POST['id_campaign']);
            } else {
                $dbCo->rollBack();
                addError('operation_update_ko');
                redirectTo();
            }
        }
    } catch (PDOException $e) {
        $dbCo->rollBack();
        addError('operation_update_ko');
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
else if ($_POST['action'] === 'modify-colour') { // Update brand colour on profil.php if you're a client boss.
    if (!isset($_POST['profile_brand'])) {
        addError('brand_ko');
        redirectTo('profil');
        exit;
    }

    if (!isset($_POST['color'])) {
        addError('colour_ko');
        redirectTo('profil');
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
            redirectTo('profil');
            exit;
        } else {
            addError('update_ko_colour');
            redirectTo('profil');
            exit;
        }
    } else {
        // Si la couleur n'est pas valide
        addError('invalid_colour_format');
        redirectTo('profil');
        exit;
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
else if ($_POST['action'] === 'new_brand') { // Create a new brand on new-brand.php.
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
        redirectTo('my-client?client=' . $_SESSION['filter']['id_company']);
    } else {
        addError('brand_creation_ko');
        redirectTo('');
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
else if ($_POST['action'] === 'create_client') { // Create a new client on dashboard.php or clients.php
    // Vérifications des champs requis
    if (!isset($_POST['company_name']) || empty($_POST['company_name']) || !is_string($_POST['company_name']) || strlen($_POST['company_name']) > 100) {
        addError('company_name_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['annual_budget']) || $_POST['annual_budget'] < 0) {
        addError('budget_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['year']) || !intval($_POST['year'])) {
        addError('year_ko');
        redirectTo();
        exit;
    }

    // Gestion du fichier attaché
    $attachmentFileName = 'default.webp'; // Valeur par défaut
    if (isset($_FILES['attachment']) && !empty($_FILES['attachment']['name'])) {

        $uploadDir = __DIR__ . '/logo/'; // Dossier de destination

        // Vérification que le dossier existe, sinon le créer
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        // Récupérer les informations du fichier
        $fileName = pathinfo($_FILES['attachment']['name'], PATHINFO_FILENAME);
        $fileExtension = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);

        // time() pour assurer un nom de fichier unique.
        $attachmentFileName = $fileName . '_' . time() . '.' . $fileExtension;
        $uploadFile = $uploadDir . $attachmentFileName;
        $relativePath = 'logo/' . $attachmentFileName;

        // Vérification de l'erreur de téléchargement
        if ($_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            // Vérification des types de fichiers autorisés
            $allowedTypes = [
                'image/png',
                'image/jpeg',
                'image/jpg',
                'image/webp'
            ];
            $fileType = mime_content_type($_FILES['attachment']['tmp_name']);

            if (in_array($fileType, $allowedTypes)) {
                // Déplacer le fichier vers le dossier de destination
                if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadFile)) {
                    $attachmentFileName = htmlspecialchars($attachmentFileName); // Nom du fichier téléchargé
                } else {
                    echo "Erreur lors du téléchargement de $attachmentFileName.<br>";
                }
            } else {
                echo "Type de fichier non autorisé pour $attachmentFileName.<br>";
            }
        } else {
            echo "Erreur de téléchargement pour le fichier $attachmentFileName.<br>";
        }
    }

    try {
        $dbCo->beginTransaction();

        // Préparation de la requête pour insérer le client
        $queryNewClient = $dbCo->prepare('INSERT INTO company (company_name, logo_url, unique_brand) VALUES (:company_name, :logo_url, :unique_brand);');
        $bindValuesClient = [
            'company_name' => htmlspecialchars($_POST['company_name']),
            'logo_url' => 'logo/' . $attachmentFileName, // Utiliser le nom du fichier téléchargé
            'unique_brand' => isset($_POST['unique_brand']) ? intval($_POST['unique_brand']) : 0
        ];

        $isInsertClientOk = $queryNewClient->execute($bindValuesClient);
        $lastInsertClient = $dbCo->lastInsertId();

        if ($isInsertClientOk) {
            $companyId = $lastInsertClient;

            // Insertion du budget
            $queryAnnualBudget = $dbCo->prepare(
                'INSERT INTO budgets (year, annual_budget, id_company)
                VALUES (:year, :annual_budget, :id_company);'
            );
            $bindValuesBudget = [
                'year' => intval($_POST['year']),
                'annual_budget' => floatval($_POST['annual_budget']),
                'id_company' => $companyId
            ];
            $isAnnualBudgetInsertOk = $queryAnnualBudget->execute($bindValuesBudget);

            if ($isAnnualBudgetInsertOk) {
                $dbCo->commit();
                addMessage('new_client_created_ok');
                redirectTo('my-client?client=' . $lastInsertClient);
                exit;
            } else {
                throw new PDOException('Failed to insert annual budget');
            }
        } else {
            throw new PDOException('Failed to insert new client');
        }
    } catch (PDOException $e) {
        $dbCo->rollBack();
        addError('database_error: ' . $e->getMessage());
        redirectTo();
        exit;
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
else if ($_POST['action'] === 'create_user') { // Create a new user on new-user.php.

    $_SESSION['form_data'] = [
        'username' => htmlspecialchars($_POST['username']),
        'firstname' => htmlspecialchars($_POST['firstname']),
        'lastname' => htmlspecialchars($_POST['lastname']),
        'company' => intval($_POST['company']),
        'password' => htmlspecialchars($_POST['password']),
        'email' => htmlspecialchars($_POST['email']),
        'phone' => htmlspecialchars($_POST['phone']),
        'status' => intval($_POST['status'])
    ];

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
        unset($_SESSION['form_data']);
        addMessage('new_user_created_ok');
        redirectTo('my-client?client=' . $_SESSION['filter']['id_company']);
    } else {
        addError('new_user_creation_ko');
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
else if ($_POST['action'] === 'create_partner') { // Create a new partner on partner.php.
    if (!isset($_POST['partner_name']) || empty($_POST['partner_name']) || !is_string($_POST['partner_name']) || strlen($_POST['partner_name']) > 255) {
        addError('partner_name_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['partner_colour']) || empty($_POST['partner_colour']) || !is_string($_POST['partner_colour']) || strlen($_POST['partner_colour']) > 255) {
        addError('partner_colour_ko');
        redirectTo();
        exit;
    }

    $queryNewPartner = $dbCo->prepare(
        'INSERT INTO partner (partner_name, partner_colour)
        VALUES (:partner_name, :partner_colour);'
    );

    $bindValues = [
        'partner_name' => htmlspecialchars($_POST['partner_name']),
        'partner_colour' => htmlspecialchars($_POST['partner_colour'])
    ];

    $isInsertPartnerOk = $queryNewPartner->execute($bindValues);

    if ($isInsertPartnerOk) {
        addMessage('partner_created_ok');
        redirectTo();
    } else {
        addError('partner_created_ko');
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
else if ($_POST['action'] === 'disable-client') { // Disable user account on dashboard.php or clients.php
    if (!isset($_POST['client-user'])) {
        addError('client_ko');
        redirectTo();
        exit;
    }

    $queryDisableClient = $dbCo->prepare(
        'UPDATE users
        SET enabled = 0 WHERE id_user = :id_user;'
    );

    $bindValues = [
        'id_user' => intval($_POST['client-user'])
    ];

    $isDisableClientOk = $queryDisableClient->execute($bindValues);

    if ($isDisableClientOk) {
        addMessage('client_disabled_ok');
        redirectTo();
    } else {
        addError('client_disabled_ko');
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
else if ($_POST['action'] === 'enable-client') { // Enable user account on dashboard.php or clients.php
    if (!isset($_POST['client-user'])) {
        addError('client_ko');
        redirectTo();
        exit;
    }

    $queryEnableClient = $dbCo->prepare(
        'UPDATE users
        SET enabled = 1 WHERE id_user = :id_user;'
    );

    $bindValues = [
        'id_user' => intval($_POST['client-user'])
    ];

    $isEnableClientOk = $queryEnableClient->execute($bindValues);

    if ($isEnableClientOk) {
        addMessage('client_enabled_ok');
        redirectTo();
    } else {
        addError('client_enabled_ko');
    }
}

// Redirige vers la dernière page si pas d'autres redirection préalable.
redirectTo();
