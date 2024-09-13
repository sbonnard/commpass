<?php
require_once '_config.php';
require_once '_database.php';
require_once '_functions.php';

$user = fetchUserDatas($dbCo, $_SESSION);

$users = fetchAllUsers($dbCo);

$companies = fetchAllCompanies($dbCo, $_SESSION);

$campaigns = getCompanyCampaigns($dbCo, $_SESSION);

$brands = getCampaignsBrands($dbCo, $_SESSION, $campaigns);