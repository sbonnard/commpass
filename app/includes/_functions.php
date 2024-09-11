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
            'SELECT id_campaign, campaign_name, budget, date, company_name, YEAR(date) AS year
            FROM campaign
                JOIN company USING (id_company)
            ORDER BY date DESC;'
        );

        $queryCampaigns->execute();

        $campaignDatas = $queryCampaigns->fetchAll();
    } else if (isset($session['client']) && $session['client'] === 1 && $session['boss'] === 1) {
        $queryCampaigns = $dbCo->prepare(
            'SELECT id_campaign, campaign_name, budget, date, YEAR(date) AS year
            FROM campaign
            WHERE id_company = :id
            ORDER BY date DESC;'
        );

        $bindValues = [
            'id' => intval($session['id_company']),
            'id_user' => intval($session['id_user'])
        ];

        $queryCampaigns->execute($bindValues);

        $campaignDatas = $queryCampaigns->fetchAll();
    } else {
        $queryCampaigns = $dbCo->prepare(
            'SELECT id_campaign, campaign_name, budget, date, YEAR(date) AS year
            FROM campaign
            WHERE id_company = :id AND id_user = :id_user
            ORDER BY date DESC;'
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

/**
 * Display company's name for a campaign if the user is not a client.
 *
 * @param array $campaigns - The array containing all campaigns.
 * @param array $session - Superglobal $_SESSION.
 * @return string - HTML elements to add to campaign.
 */
function getCompanyNameIfTDC(array $campaigns, array $session): string
{
    if (isset($session['client']) && $session['client'] === 0) {
        return '<h4 class="ttl ttl--small ttl--small-lowercase">' . $campaigns['company_name'] . '</h4>';
    }
}


/**
 * Get HTML template for a campaign displaying most important infos.
 *
 * @param array $campaigns - An array containing all campaigns.
 * @param array $session - Superglobal $_SESSION.
 * @return string - HTML code that constitutes the template.
 */
function getCampaignTemplate(array $campaigns, array $session): string
{
    $campaignList = '';

    foreach ($campaigns as $campaign) {
        $campaignList .= '
        <a href="?campaign=' . $campaign['id_campaign'] . '">
            <div class="card__section">
                <div class="campaign__ttl">
                    <h3 class="ttl ttl--small">' . $campaign['campaign_name'] . '</h3>'
            . getCompanyNameIfTDC($campaign, $session) .
            '</div>
                <div class="campaign__stats">
                    <img src="img/chart.webp" alt="Graphique camembert récapitulatif de la campagne ' . $campaign['campaign_name'] . '">

                    <div class="vignettes-section">
                        <div class="vignette vignette--primary">
                            <h4 class="vignette__ttl">
                                Budget attribué
                            </h4>
                            <p class="vignette__price">' . $campaign['budget'] . ' €</p>
                        </div>
                        <div class="vignette vignette--secondary">
                            <h4 class="vignette__ttl">
                                Budget dépensé
                            </h4>
                            <p class="vignette__price">13 562.05 €</p>
                        </div>
                        <div class="vignette vignette--tertiary">
                            <h4 class="vignette__ttl">
                                Budget restant
                            </h4>
                            <p class="vignette__price">14 437.95 €</p>
                        </div>
                    </div>
                </div>
                <div class="campaign__legend-section">
                    <p class="campaign__legend">Lumosphère</p>
                    <p class="campaign__legend">Vélocitix</p>
                    <p class="campaign__legend">Stellar Threads</p>
                    <p class="campaign__legend">Aurélys</p>
                    <p class="campaign__legend">Toutes les marques</p>
                </div>
            </div>
        </a>
        ';
    }

    return $campaignList;
}
