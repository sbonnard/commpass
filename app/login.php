<?php
session_start();

require_once 'includes/_config.php';
require_once 'includes/_functions.php';
require_once 'includes/_database.php';
require_once 'includes/_message.php';
require_once 'includes/_security.php';

// header('Content-type:application/json');


if (!isset($_POST['action'])) {
    addError('no_action');
}

// Check CSRF
preventFromCSRF('index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'log-in') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $dbCo->prepare('SELECT * FROM users WHERE username = :username');
    $query->execute(['username' => $username]);
    $user = $query->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['lastname'] = $user['lastname'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['client'] = $user['client'];
        $_SESSION['id_company'] = $user['id_company'];
        redirectTo('dashboard.php');
        exit();
    } else {
        addError('login_fail');
        redirectTo('index.php');
    }
}