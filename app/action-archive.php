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
    redirectTo('/dashboard.php');
    exit;
}

// Check CSRF
preventFromCSRF();

if ($_POST['action'] === 'archive-year') {
    
}
