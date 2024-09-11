<?php

global $dbCo;

// $RPG = fetchRPG($dbCo);
// $parties = getPartyDatas($dbCo);
// $partiesDatas = getPartyDatasOnly($dbCo);

/**
 * Generates a random token for forms to prevent from CSRF. It also generate a new token after 15 minutes.
 *
 * @return void
 */
function generateToken()
{
    if (
        !isset($_SESSION['token'])
        || !isset($_SESSION['tokenExpire'])
        || $_SESSION['tokenExpire'] < time()
    ) {
        $_SESSION['token'] = md5(uniqid(mt_rand(), true));
        $_SESSION['tokenExpire'] = time() + 60 * 15;
    }
}


/**
 * Redirect to the given URL or to the previous page if no URL is provided.
 *
 * @param string|null $url URL to redirect to. If null, redirect to the previous page.
 * @return void
 */
function redirectTo(?string $url = null): void
{
    if ($url === null) {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
        } else {
            $url = 'defaultPage.php'; // Fallback URL if HTTP_REFERER is not set
        }
    }
    header('Location: ' . $url);
    exit;
}

/**
 * Get company name with a query to server and by checking $_SESSION datas.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $session - Superglobal $_SESSION.
 * @return string - The name of the company.
 */
function getCompanyName(PDO $dbCo, array $session): string
{
    $query = $dbCo->prepare(
        'SELECT company_name
        FROM company
        WHERE id_company = :id;'
    );

    $bindValues = [
        'id' => intval($session['id_company'])
    ];

    $query->execute($bindValues);

    $companyDatas = $query->fetch();

    return implode($companyDatas);
};


/**
 * Get communication campaigns considering your status of client or not, your company and if you are the inrlocutor or not.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $session - Superglobal $_SESSION.
 * @return array $campaignDatas - An array containing all your campaigns datas.
 */
function getCompanyCampaigns(PDO $dbCo, array $session): array
{
    if (isset($session['client']) && $session['client'] === 0 && $session['boss'] === 1) {
        $queryCampaigns = $dbCo->prepare(
            'SELECT id_campaign, campaign_name, budget, date
            FROM campaign;'
        );

        $queryCampaigns->execute();

        $campaignDatas = $queryCampaigns->fetchAll();
    } else if (isset($session['client']) && $session['client'] === 1 && $session['boss'] === 1) {
        $queryCampaigns = $dbCo->prepare(
            'SELECT id_campaign, campaign_name, budget, date
            FROM campaign
            WHERE id_company = :id;'
        );

        $bindValues = [
            'id' => intval($session['id_company']),
            'id_user' => intval($session['id_user'])
        ];

        $queryCampaigns->execute($bindValues);

        $campaignDatas = $queryCampaigns->fetchAll();
    } else {
        $queryCampaigns = $dbCo->prepare(
            'SELECT id_campaign, campaign_name, budget, date
            FROM campaign
            WHERE id_company = :id AND id_user = :id_user;'
        );

        $bindValues = [
            'id' => intval($session['id_company']),
            'id_user' => intval($session['id_user'])
        ];

        $queryCampaigns->execute($bindValues);

        $campaignDatas = $queryCampaigns->fetchAll();
    }

    return $campaignDatas;
}
