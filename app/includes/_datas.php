<?php
require_once '_config.php';
require_once '_database.php';
require_once '_functions.php';

// CLASSES

require_once "includes/classes/class.brand.php";
require_once "includes/classes/class.campaign.php";
require_once "includes/classes/class.company.php";
require_once "includes/classes/class.user.php";

$user = fetchUserDatas($dbCo, $_SESSION);

$users = fetchAllUsers($dbCo);

$companies = fetchAllCompanies($dbCo, $_SESSION);

$campaigns = getCompanyCampaigns($dbCo, $_SESSION);

$selectedCampaign = getOneCampaignDatas($dbCo, $_GET);

$campaignOperations = getCampaignOperations($dbCo, $_GET);

$brands = getCampaignsBrands($dbCo, $_SESSION, $campaigns);