<?php
session_start();

//CONFIG AND CONNECTION
require_once "includes/_config.php";
require_once "includes/_database.php";

// DATAS
// require_once "includes/_datas.php";

// FUNCTIONS
require_once "includes/_functions.php";
require_once "includes/_security.php";
require_once "includes/_message.php";


header('Content-type:application/json');


if (!isset($_POST['action'])) {
    addError('no_action');
}

// Check CSRF
preventFromCSRF('index');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'log-in') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $query = $dbCo->prepare('SELECT * FROM users WHERE username = :username');
    $query->execute(['username' => $username]);
    $user = $query->fetch();

    if ($user && password_verify($password, $user['password']) && $user['enabled'] === 1) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['client'] = $user['client'];
        $_SESSION['boss'] = $user['boss'];
        $_SESSION['id_company'] = $user['id_company'];
    } else {
        addError('login_fail');
        redirectTo('index');
    }
}

redirectTo('dashboard');