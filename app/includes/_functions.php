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

function getCompanyCampaigns(PDO $dbCo, array $session)
{
    if (isset($session['client']) && $session['client'] === 0) {
        $queryCampaigns = $dbCo->prepare(
            'SELECT id_campaign, campaign_name, budget, date
            FROM campaign;'
        );

        $queryCampaigns->execute();

        $campaignDatas = $queryCampaigns->fetchAll();

    } else {
        $queryCampaigns = $dbCo->prepare(
            'SELECT id_campaign, campaign_name, budget, date
            FROM campaign
            WHERE id_company = :id;'
        );

        $bindValues = [
            'id' => intval($session['id_company'])
        ];

        $queryCampaigns->execute($bindValues);

        $campaignDatas = $queryCampaigns->fetchAll();
    }

    return $campaignDatas;
}
