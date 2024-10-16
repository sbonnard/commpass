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
    redirectTo('dashboard');
    exit;
}

// Check CSRF
preventFromCSRF();

if ($_POST['action'] === 'filter-campaigns') {
    if (!isset($_POST['client-filter']) || empty($_POST['client-filter'])) {
        addError('no_client');
        redirectTo('dashboard');
        exit;
    }

    if (isset($_POST['client-filter']) && intval($_POST['client-filter'])) {
        $_SESSION['filter']['id_company'] = intval($_POST['client-filter']);
    }

    if (
        isset($_POST['target-filter']) && intval($_POST['target-filter'])
        && intval($_POST['target-filter']) >= 1 && intval($_POST['target-filter']) <= 3
    ) {
        $_SESSION['filter']['id_target'] = intval($_POST['target-filter']);
    }
} else if ($_POST['action'] === 'filter-history') {
    if (isset($_POST['client-filter']) && intval($_POST['client-filter'])) {
        $_SESSION['filter']['id_company'] = intval($_POST['client-filter']);
    }

    if (isset($_POST['year'])) {
        $_SESSION['filter']['year'] = htmlspecialchars($_POST['year']);
    }

    if (isset($_POST['target-filter']) && intval($_POST['target-filter']) && intval($_POST['target-filter']) >= 1 && intval($_POST['target-filter']) <= 3) {
        $_SESSION['filter']['id_target'] = intval($_POST['target-filter']);
    }
} else if (isset($_POST['action']) && $_POST['action'] === 'filter-reinit') {
    unset($_SESSION['filter']);
}

redirectTo();
