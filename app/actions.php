<?php
session_start();

require_once 'includes/_config.php';
require_once 'includes/_functions.php';
require_once 'includes/_database.php';
require_once 'includes/_message.php';
require_once 'includes/_security.php';

header('Content-type:application/json');


if (!isset($_REQUEST['action'])) {
    redirectTo('profil.php');
}


// Check CSRF
preventFromCSRF();

if ($_POST['action'] === 'modify-pwd') {
    $queryPWD = $dbCo->prepare('UPDATE users SET password = :password WHERE id_user = :id_user;');

    $bindValues = [
        'password' => password_hash(strip_tags($_POST['password']), PASSWORD_BCRYPT),
        'id_user' => strip_tags($_SESSION['id_user']),
    ];

    $isUpdateOk = $queryPWD->execute($bindValues);

    if ($isUpdateOk) {
        $_SESSION['msg'] = "update_ok_pwd";
    } else {
        $_SESSION['msg'] = "update_ko_pwd";
    }
}

redirectTo('profil.php');