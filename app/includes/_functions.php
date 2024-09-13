<?php

global $dbCo;

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

function fetchUserDatas(PDO $dbCo, array $session): array
{
    $query = $dbCo->prepare(
        'SELECT id_user, username, firstname, lastname, email, phone, client, boss, id_company
        FROM users
        WHERE id_user = :id;'
    );

    $bindValues = [
        'id' => intval($session['id_user'])
    ];

    $query->execute($bindValues);

    $userData = $query->fetch();

    return $userData;
}


/**
 * Fetch all users from the database.
 *
 * @param PDO $dbCo - Connection to database.
 * @return array - User datas.
 */
function fetchAllUsers(PDO $dbCo): array
{
    $query = $dbCo->prepare(
        'SELECT id_user, username, firstname, lastname, email, phone, client, boss, id_company
        FROM users;'
    );

    $query->execute();

    $userData = $query->fetchAll();

    return $userData;
}


/**
 * Fetch all companies from the database.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $session - Superglobal $_SESSION.
 * @return array - Company datas.
 */
function fetchAllCompanies(PDO $dbCo, array $session): array
{
    $companyDatas = [];

    // The user only has access to datas if he is not a client.
    if (isset($session['client']) && $session['client'] === 0) {
        try {
            $query = $dbCo->prepare(
                'SELECT id_company, company_name
                FROM company
                ORDER BY company_name;'
            );

            $query->execute();

            $companyDatas = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des entreprises : " . $e->getMessage());
            return [];
        }
    }

    return $companyDatas;
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
 * Get options for whatever datas you set as parameters.
 *
 * @param array $datas - The array containing the datas.
 * @param string $id - The id field in the array.
 * @param string $dataName - The name of the the interlocutor or company.
 * @return string - The HTML options for the select field.
 */
function getDatasAsHTMLOptions(array $datas, string $placeholder, string $id, string $dataName): string
{
    $htmlOptions = '<option class="form__input__placeholder" value="">- ' . $placeholder . ' -</option>';

    foreach ($datas as $data) {
        $htmlOptions .=
            '<option value="' . htmlspecialchars($data[$id], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($data[$dataName], ENT_QUOTES, 'UTF-8') .
            '</option>';
    }

    return $htmlOptions;
}


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
        // Si l'utilisateur est le gérant de l'entreprise Toile de Com.
        $queryCampaigns = $dbCo->prepare(
            'SELECT id_campaign, campaign_name, budget, date, company_name, YEAR(date) AS year
            FROM campaign
                JOIN company USING (id_company)
            ORDER BY date DESC;'
        );
        $queryCampaigns->execute();

        $campaignDatas = $queryCampaigns->fetchAll();
    } else if (isset($session['client']) && $session['client'] === 1 && $session['boss'] === 1) {
        // Si l'utilisateur est un client mais qu'il est aussi le gérant de l'entreprise cliente.
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
        // Si l'utilisateur est un client mais qu'il n'est pas gérant de l'entreprise. Il est donc simple interlocuteur sur ses campagnes.
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
    } else {
        return '';
    }
}


/**
 * Get HTML template for a campaign displaying most important infos.
 *
 * @param array $campaigns - An array containing all campaigns.
 * @param array $session - Superglobal $_SESSION.
 * @return string - HTML code that constitutes the template.
 */
function getCampaignTemplate(PDO $dbCo, array $campaigns, array $brands, array $session): string
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
                            <p class="vignette__price">' . formatPrice($campaign['budget'], "€") . '</p>
                        </div>
                        <div class="vignette vignette--secondary">
                            <h4 class="vignette__ttl">
                                Budget dépensé
                            </h4>
                            <p class="vignette__price">' . calculateSpentBudget($dbCo, $campaign) . '</p>
                        </div>
                        <div class="vignette vignette--tertiary">
                            <h4 class="vignette__ttl">
                                Budget restant
                            </h4>
                            <p class="vignette__price">' . calculateRemainingBudget($dbCo, $campaign) . '</p>
                        </div>
                    </div>
                </div>
                <ul class="campaign__legend-section">'
            . getBrandsAsList($brands) .
            '<li class="campaign__legend">Toutes les marques</li>
                </ul>
            </div>
        </a>
        ';
    }

    return $campaignList;
}

//  Not working for now.
// function getCampaignsByYear(array $campaigns, array $session, string $date)
// {
//     $campaignList = '';

//     foreach ($campaigns as $campaign) {
//             if(str_contains($campaign['date'], $date)) {
//                 $campaignList .= '
//                     <h2 class="ttl ttl--secondary">
//                         Campagnes ' . $date . '
//                     </h2>'
//                     . getCampaignTemplate($campaign, $session);
//             }
//         }

//     return $campaignList;
// }


/**
 * Get a message if you don't have any campaign on your dashboard
 *
 * @param array $campaigns - Array containing all campaigns.
 * @return string - HTML to display a message.
 */
function getMessageIfNoCampaign(array $campaigns): string
{
    if (empty($campaigns)) {
        return '
        <div class="card card__section">
            <p class="big-text">Vous n\'avez pas encore de campagnes de comm\' !</p>
        </div>
        ';
    }

    return '';
}

/**
 * Formats a price in a specific way. Example : 25000.00 -> 25 000 € OR 18000.50 -> 18 000,50 €.
 *
 * @param float $price - The price to format.
 * @param string $currency - The currency you want to apply to the price.
 * @return string - The price formated.
 */
function formatPrice(float|int $price, string $currency): string
{
    if ($price == (int)$price) {
        return number_format($price, 0, ',', ' ') . ' ' . $currency;
    } else {
        return number_format($price, 2, ',', ' ') . ' ' . $currency;
    }
}

/**
 * Calculates the sum that is already spent for a campaign.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $campaigns - The array containing all campaigns datas.
 * @return string - A price formated thanks to formatPrice() function.
 */
function calculateSpentBudget(PDO $dbCo, array $campaigns): string
{
    $querySum = $dbCo->prepare(
        'SELECT SUM(price) AS total_cost
        FROM operation
        WHERE id_campaign = :id_campaign;'
    );

    $bindValues = [
        'id_campaign' => intval($campaigns['id_campaign'])
    ];

    $querySum->execute($bindValues);

    $result = $querySum->fetch(PDO::FETCH_ASSOC);

    return formatPrice(floatval($result['total_cost'] ?? 0), '€');
}

/**
 * Calculates remaining budget of a campaign.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $campaigns - The array containing all campaigns datas. 
 * @return string - A price formated thanks to formatPrice() function.
 */
function calculateRemainingBudget(PDO $dbCo, array $campaigns): string
{
    $queryRemaining = $dbCo->prepare(
        'SELECT c.id_campaign, (c.budget - IFNULL(SUM(o.price), 0)) AS total_remaining 
        FROM campaign c
            LEFT JOIN operation o ON c.id_campaign = o.id_campaign
        WHERE c.id_campaign = :id_campaign
        GROUP BY c.id_campaign;'
    );

    $bindValues = [
        'id_campaign' => intval($campaigns['id_campaign'])
    ];

    $queryRemaining->execute($bindValues);

    $result = $queryRemaining->fetch(PDO::FETCH_ASSOC);

    return formatPrice(floatval($result['total_remaining'] ?? 0), '€');
}

/**
 * Get brands that were spotlighted during a campaign. 
 * If user is a client, id_company is taken from $_SESSION whereas id_company is taken from $campaigns if user is from Toile de Com.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $session - Superglobal $_SESSION
 * @param array $campaigns - An array containing all campaigns.
 * @return array - An array containing all brands from a company.
 */
function getCampaignsBrands(PDO $dbCo, array $session, array $campaigns): array
{
    if (isset($session['id_company']) && intval($session['id_company'])) {
        if ($session['client'] === 1) {
            $queryBrands = $dbCo->prepare(
                'SELECT *
                FROM brand
                WHERE id_company = :id_company;'
            );

            $bindValues = [
                'id_company' => intval($session['id_company'])
            ];

            $queryBrands->execute($bindValues);
        } else {
            $queryBrands = $dbCo->query(
                'SELECT *
                FROM brand;)'
            );
        }


        $brands = $queryBrands->fetchAll(PDO::FETCH_ASSOC);

        return $brands;
    } else {
        return '';
    }
}

/**
 * Get a list of brand as HTML elements (<li>).
 *
 * @param array $brands - array of brands.
 * @return string - A list of brand names that appear in a campaign.
 */
function getBrandsAsList(array $brands): string
{
    $brandList = '';

    foreach ($brands as $brand) {
        $brandList .= '
        <li class="campaign__legend"><span class="campaign__legend-square" style="background-color:' . $brand['legend_colour_hex'] . '"></span>' . $brand['brand_name'] . '</li>
        ';
    }

    return $brandList;
}
