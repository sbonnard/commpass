<?php

$user = fetchUserDatas($dbCo, $_SESSION);

$users = fetchAllUsers($dbCo);

$companies = fetchAllCompanies($dbCo, $_SESSION);

$campaigns = getCompanyCampaigns($dbCo, $_SESSION);

$brands = getCampaignsBrands($dbCo, $_SESSION, $campaigns);