<?php


/**
 * Get communication campaigns considering your status of client or not, your company and if you are the inrlocutor or not.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $session - Superglobal $_SESSION.
 * @return array $campaignDatas - An array containing all your campaigns datas.
 */
function getCompanyCampaigns(PDO $dbCo, array $session): array
{
    if (!isset($session['client'], $session['boss'], $session['id_company'], $session['id_user'])) {
        return [];
    }

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
 * If the user is a client, id_company is taken from $_SESSION; 
 * otherwise, id_company is taken from $campaigns if the user is from Toile de Com.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $session - Superglobal $_SESSION
 * @param array $campaigns - An array containing all campaigns.
 * @return array - An array containing all brands from a company.
 */
function getCampaignsBrands(PDO $dbCo, array $session, array $campaigns): array
{
    $brands = []; // Initialisation du tableau de retour

    if (isset($session['id_company']) && intval($session['id_company'])) {
        if ($session['client'] === 1) {
            // Requête pour les clients, en utilisant id_company depuis la session
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
            // Requête pour les utilisateurs de Toile de Com, en utilisant les campagnes
            // Supposons que vous souhaitez obtenir les marques en fonction des campagnes
            // Vous pouvez adapter cette requête selon votre logique spécifique
            $queryBrands = $dbCo->prepare(
                'SELECT DISTINCT brand.*
                FROM brand
                JOIN campaign ON brand.id_company = campaign.id_company
                WHERE campaign.id_campaign IN (' . implode(',', array_map('intval', array_column($campaigns, 'id_campaign'))) . ');'
            );

            $queryBrands->execute();
        }

        // Récupérer les résultats
        $brands = $queryBrands->fetchAll(PDO::FETCH_ASSOC);
    }

    return $brands; // Retourner un tableau même si aucune marque n'est trouvée
}


function filterCampaigns(PDO $dbCo, array $campaigns)
{
    if (!isset($_POST['date-from'], $_POST['date-to'])) {
        addError('date_ko');
        redirectTo();
        exit;
    }

    $dateFrom = sanitizeInput($_POST['date-from']);
    $dateTo = sanitizeInput($_POST['date-to']);

    $queryFilter = $dbCo->prepare(
        'SELECT * 
        FROM campaigns 
        WHERE date 
        BETWEEN :dateFrom AND :dateTo;'
    );

    $bindValues = [
        'dateFrom' => $dateFrom,
        'dateTo' => $dateTo
    ];

    $queryFilter->execute($bindValues);

    $campaigns = $queryFilter->fetchAll();

    echo json_encode($campaigns);
}
