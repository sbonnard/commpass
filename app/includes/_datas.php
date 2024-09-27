<?php
require_once '_config.php';
require_once '_database.php';
require_once '_functions.php';

// CLASSES

require_once "includes/classes/class.brand.php";
require_once "includes/classes/class.campaign.php";
require_once "includes/classes/class.company.php";
require_once "includes/classes/class.operation.php";
require_once "includes/classes/class.user.php";
require_once "includes/classes/class.media.php";
require_once "includes/classes/class.partner.php";


// USER DATAS
$user = fetchUserDatas($dbCo, $_SESSION);

$users = fetchAllUsers($dbCo);

// COMPANY DATAS
$companies = fetchAllCompanies($dbCo, $_SESSION);

$companyBrands = getCompanyBrands($dbCo, $_GET);

$companyAnnualBudget = fetchCompanyAnnualBudget($dbCo, $_SESSION);

$companyAnnualRemainings = calculateAnnualRemainingBudget($dbCo, $_SESSION);

$companyCurrentYearCampaigns = getOneCompanyYearlyCampaigns($dbCo, $_SESSION);

// CAMPAIGN DATAS
$campaigns = getCompanyCampaigns($dbCo, $_SESSION);

$currentYearCampaigns = getCompanyCampaignsCurrentYear($dbCo, $_SESSION, '=', date('Y'));

$pastYearsCampaigns = getCompanyCampaignsPastYears($dbCo, $_SESSION, '!=', date('Y'));

$selectedCampaign = getOneCampaignDatas($dbCo, $_GET);

$brands = getCampaignsBrands($dbCo, $_SESSION, $campaigns);

$campaignRemainings = calculateRemainingBudget($dbCo, $selectedCampaign);

$eachCampaignRemainings = calculateRemainingBudget($dbCo, $campaigns);

// OPERATION DATAS
$campaignOperations = getCampaignOperations($dbCo, $_GET);

$operation = getAllOperationsFromACampaign($dbCo, $_GET);


// OTHER DATAS
$allbrands = fetchAllBrands($dbCo);

$communicationObjectives = fetchCampaignTarget($dbCo);

$media = fetchAllMedia($dbCo);

$partners = fetchAllPartners($dbCo);

$currentYear = date('Y');
