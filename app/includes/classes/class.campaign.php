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

    if ($session['client'] === 0 && $session['boss'] === 1) {
        // Si l'utilisateur est le gérant de l'entreprise Toile de Com.
        $queryCampaigns = $dbCo->prepare(
            'SELECT id_campaign, campaign_name, budget, date_start, date_end, company.id_company, company_name, YEAR(date_start) AS year
            FROM campaign
                JOIN company USING (id_company)
            ORDER BY date_start DESC;'
        );
        $queryCampaigns->execute();
    } else if ($session['client'] === 0 && $session['boss'] === 0) {
        // Si l'utilisateur est l'employé de l'entreprise Toile de Com.
        $queryCampaigns = $dbCo->prepare(
            'SELECT id_campaign, campaign_name, budget, date_start, date_end, company_name, company.id_company, YEAR(date_start) AS year
            FROM campaign
                JOIN company USING (id_company)
            WHERE id_user_TDC = :id_user
            ORDER BY date_start DESC;'
        );

        $bindValues = [
            'id_user' => intval($session['id_user'])
        ];
        $queryCampaigns->execute($bindValues);
    } else if ($session['client'] === 1 && $session['boss'] === 1) {
        // Si l'utilisateur est un client mais qu'il est aussi le gérant de l'entreprise cliente.
        $queryCampaigns = $dbCo->prepare(
            'SELECT id_campaign, campaign_name, campaign.id_company, budget, date_start, date_end, YEAR(date_start) AS year
            FROM campaign
            WHERE id_company = :id
            ORDER BY date_start DESC;'
        );

        $bindValues = [
            'id' => intval($session['id_company']),
        ];
        $queryCampaigns->execute($bindValues);
    } else {
        // Si l'utilisateur est un client mais qu'il n'est pas gérant de l'entreprise.
        $queryCampaigns = $dbCo->prepare(
            'SELECT id_campaign, campaign_name, campaign.id_company, budget, date_start, date_end, YEAR(date_start) AS year
            FROM campaign
            WHERE id_company = :id AND id_user = :id_user
            ORDER BY date_start DESC;'
        );
        $bindValues = [
            'id' => intval($session['id_company']),
            'id_user' => intval($session['id_user'])
        ];
        $queryCampaigns->execute($bindValues);
    }

    return $queryCampaigns->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Get company campaigns for current year considering user's status as client or not, their company, and if they are the interlocutor.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $session - Superglobal $_SESSION.
 * @return array - array of campaigns for current year.
 */
function getCompanyCampaignsCurrentYear(PDO $dbCo, array $session): array
{
    if (!isset($session['client'], $session['boss'], $session['id_company'], $session['id_user'])) {
        return [];
    }

    $baseQuery =
        'SELECT id_campaign, campaign_name, budget, date_start, date_end, company.id_company, company_name, YEAR(date_start) AS year, target.id_target, target_com
        FROM campaign
            JOIN company USING (id_company)
            JOIN target USING (id_target)
        WHERE (YEAR(date_start) = YEAR(CURDATE()) OR YEAR(date_end) = YEAR(CURDATE()))';

    $bindValues = [];

    // Determine the appropriate conditions based on the user's status
    if ($session['client'] === 0) {
        // If the user is from Toile de Com
        if ($session['boss'] === 1) {
            // Gérant de Toile de Com
            $queryCampaigns = $baseQuery . ' ORDER BY company.id_company, date_start DESC;';
        } else {
            // Employé de Toile de Com
            $queryCampaigns = $baseQuery . ' AND id_user_TDC = :id_user ORDER BY company.id_company, date_start DESC;';
            $bindValues['id_user'] = intval($session['id_user']);
        }
    } else {
        // If the user is a client
        if ($session['boss'] === 1) {
            // Gérant d’une entreprise cliente
            $queryCampaigns = $baseQuery . ' AND company.id_company = :id ORDER BY company.id_company, date_start DESC;';
            $bindValues['id'] = intval($session['id_company']);
        } else {
            // Interlocuteur d’une entreprise cliente
            $queryCampaigns = $baseQuery . ' AND company.id_company = :id AND id_user = :id_user ORDER BY company.id_company, date_start DESC;';
            $bindValues['id'] = intval($session['id_company']);
            $bindValues['id_user'] = intval($session['id_user']);
        }
    }

    // Prepare and execute the query
    $queryCampaigns = $dbCo->prepare($queryCampaigns);
    $queryCampaigns->execute($bindValues);

    // Fetch and return results
    return $queryCampaigns->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Get campaigns for past years considering your status of client or not, your company and if you are the inrlocutor or not.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $session - Superglobal $_SESSION.
 * @return array - array of campaigns for past years.
 */
function getCompanyCampaignsPastYears(PDO $dbCo, array $session, $campaigns, $year = null): array
{
    if (!isset($session['client'], $session['boss'], $session['id_company'], $session['id_user'])) {
        return [];
    }

    $yearCondition = $year ? ' AND YEAR(date_start) = :year' : ' AND YEAR(date_start) < YEAR(CURDATE())';

    if (isset($session['client']) && $session['client'] === 0 && $session['boss'] === 1) {
        // Si l'utilisateur est le gérant de l'entreprise Toile de Com.
        $queryCampaigns = $dbCo->prepare(
            "SELECT id_campaign, campaign_name, budget, date_start, company.id_company, company_name, YEAR(date_start) AS year, target.id_target, target_com
            FROM campaign
                JOIN company USING (id_company)
                JOIN target USING (id_target)
            WHERE 1=1 $yearCondition
            ORDER BY id_company, date_start DESC;"
        );

        $bindValues = $year ? ['year' => intval($year)] : [];
    } else if (isset($session['client']) && $session['client'] === 0 && $session['boss'] === 0) {
        // Si l'utilisateur est le gérant de l'entreprise Toile de Com.
        $queryCampaigns = $dbCo->prepare(
            "SELECT id_campaign, campaign_name, budget, date_start, company.id_company, company_name, YEAR(date_start) AS year, target.id_target, target_com
            FROM campaign
                JOIN company USING (id_company)
                JOIN target USING (id_target)
            WHERE id_user_TDC = :id_user $yearCondition
            ORDER BY id_company, date_start DESC;"
        );

        $bindValues = [
            'id_user' => intval($session['id_user']),
        ];

        if ($year) {
            $bindValues['year'] = intval($year);
        }
    } else if (isset($session['client']) && $session['client'] === 1 && $session['boss'] === 1) {
        // Si l'utilisateur est un client mais qu'il est aussi le gérant de l'entreprise cliente.
        $queryCampaigns = $dbCo->prepare(
            "SELECT id_campaign, campaign_name, id_company, budget, date_start, YEAR(date_start) AS year, target.id_target, target_com
            FROM campaign
                JOIN target USING (id_target)
            WHERE id_company = :id $yearCondition
            ORDER BY id_company, date_start DESC;"
        );

        $bindValues = [
            'id' => intval($session['id_company']),
        ];

        if ($year) {
            $bindValues['year'] = intval($year);
        }
    } else {
        // Si l'utilisateur est un client mais qu'il n'est pas gérant de l'entreprise. Il est donc simple interlocuteur sur ses campagnes.
        $queryCampaigns = $dbCo->prepare(
            "SELECT id_campaign, campaign_name, id_company, budget, date_start, YEAR(date_start) AS year, target.id_target, target_com
            FROM campaign
                JOIN target USING (id_target)
            WHERE id_company = :id AND id_user = :id_user $yearCondition
            ORDER BY id_company, date_start DESC;"
        );

        $bindValues = [
            'id' => intval($session['id_company']),
            'id_user' => intval($session['id_user']),
        ];

        if ($year) {
            $bindValues['year'] = intval($year);
        }
    }

    $queryCampaigns->execute($bindValues);

    $campaignDatas = $queryCampaigns->fetchAll(PDO::FETCH_ASSOC);

    return $campaignDatas;
}


function getOneCompanyPastYearCampaigns(PDO $dbCo, array $session, $year = null)
{
    $yearCondition = $year ? ' AND YEAR(date_start) = :year' : ' AND YEAR(date_start) < YEAR(CURDATE())';

    $query = $dbCo->prepare(
        'SELECT id_campaign, campaign_name, budget, date_start, company.id_company, company_name, YEAR(date_start) AS year, target.id_target, target_com
            FROM campaign
                JOIN company USING (id_company)
                JOIN target USING (id_target)
            WHERE 1=1 ' . $yearCondition . ' AND campaign.id_company = :id_company
            ORDER BY date_start DESC;'
    );

    $bindValues = $year ? ['year' => intval($year)] : [];

    $bindValues['id_company'] = intval($session['filter']['id_company']);

    $query->execute($bindValues);

    $campaignDatas = $query->fetchAll(PDO::FETCH_ASSOC);

    return $campaignDatas;
}


/**
 * Get filtered campaigns for current year considering your status of client or not, your company and if you are the inrlocutor or not.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $session - Superglobal $_SESSION.
 * @return array - array of campaigns filtered.
 */
function getCompanyFilteredCampaigns(PDO $dbCo, array $session): array
{
    if (!isset($session['id_company'], $session['id_user'])) {
        return [];
    }

    $query = '
        SELECT id_campaign, campaign_name, budget, date_start, company_name, YEAR(date_start) AS year_start, YEAR(date_end) AS year_end, target.id_target, target_com
        FROM campaign
            JOIN company USING (id_company)
            JOIN target USING (id_target)
        WHERE id_company = :id_company ';

    // Ajout du filtre id_target s'il est défini
    if (isset($session['filter']['id_target'])) {
        $query .= 'AND id_target = :id_target ';
    }

    // Ajout du filtre id_user si défini et pas boss
    if ($session['client'] === 0 && $session['boss'] === 0) {
        $query .= 'AND id_user_TDC = :id_user_TDC ';
    }

    $query .= 'HAVING year_start = YEAR(CURDATE()) OR year_end = YEAR(CURDATE())
        ORDER BY id_company, date_start DESC;';

    $queryCampaigns = $dbCo->prepare($query);

    // Préparation des valeurs à binder
    $bindValues = ['id_company' => intval($session['filter']['id_company'] ?? $session['id_company'])];

    if (isset($session['filter']['id_target'])) {
        $bindValues['id_target'] = intval($session['filter']['id_target']);
    }

    if ($session['client'] === 0 && $session['boss'] === 0) {
        $bindValues['id_user_TDC'] = intval($session['id_user']);
    }

    $queryCampaigns->execute($bindValues);

    return $queryCampaigns->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Get HTML template for a campaign displaying most important infos.
 *
 * @param array $campaigns - An array containing all campaigns.
 * @param array $session - Superglobal $_SESSION.
 * @return string - HTML code that constitutes the template.
 */
function getCampaignTemplate(PDO $dbCo, array $campaigns, array $session): string
{
    $campaignList = '<div class="campaign__pattern">';


    foreach ($campaigns as $campaign) {
        $campaignId = $campaign['id_campaign'];

        $campaignList .= '
        <a href="campaign.php?myc=' . $campaignId . '">
            <div class="card__section" data-card="">
                <div class="campaign__ttl">
                    <h3 class="ttl ttl--small">' . $campaign['campaign_name'] . ' - ' . getYearOnly($dbCo, $campaign) . '</h3>'
            . getCompanyNameIfTDC($campaign, $session) .
            $campaign['target_com'] .
            '</div>
                <div class="campaign__stats">

                    <div class="js-chart" id="chart-' . $campaignId . '"></div>

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
                        <div class="vignette vignette--tertiary ' . turnVignetteRedIfNegative(calculateRemainingBudget($dbCo, $campaign)) . '">
                            <h4 class="vignette__ttl">
                                Budget restant
                            </h4>
                            <p class="vignette__price">' . calculateRemainingBudget($dbCo, $campaign) . '</p>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        ';
    }

    $campaignList .= '</div>';

    return $campaignList;
}

/**
 * Get HTML template for a campaign displaying most important infos. It displays campaigns by company.
 *
 * @param array $campaigns - An array containing all campaigns.
 * @param array $session - Superglobal $_SESSION.
 * @param array $companies - Tableau contenant toutes les entreprises.
 * @return string - HTML code that constitutes the template.
 */
function getCampaignTemplateByCompany(PDO $dbCo, array $campaigns, array $session, array $companies): string
{
    $campaignList = '';

    foreach ($companies as $company) {
        // On initialise un drapeau pour vérifier si l'entreprise a des campagnes
        $hasCampaigns = false;

        // Récupère les campagnes de l'entreprise
        $companyCampaigns = '';

        foreach ($campaigns as $campaign) {
            // Vérifie si la campagne appartient bien à l'entreprise en cours
            if ($campaign['id_company'] === $company['id_company']) {

                $hasCampaigns = true;
                $campaignId = $campaign['id_campaign'];

                $companyCampaigns .= '
                    <li>
                    <a href="campaign.php?myc=' . $campaignId . '">
                        <div class="card__section" data-card="">
                            <div class="campaign__ttl">
                                    <h3 class="ttl ttl--small">' . $campaign['campaign_name'] . ' - ' . getYearOnly($dbCo, $campaign) . '</h3>'
                    . getCompanyNameIfTDC($campaign, $session) .
                    $campaign['target_com'] .
                    '</div>
                            <div class="campaign__stats">
                                <div class="js-chart" id="chart-' . $campaignId . '"></div>
                                <div class="vignettes-section">
                                    <div class="vignette vignette--primary">
                                        <h4 class="vignette__ttl">Budget attribué</h4>
                                        <p class="vignette__price">' . formatPrice($campaign['budget'], "€") . '</p>
                                    </div>
                                    <div class="vignette vignette--secondary">
                                        <h4 class="vignette__ttl">Budget dépensé</h4>
                                        <p class="vignette__price">' . calculateSpentBudget($dbCo, $campaign) . '</p>
                                    </div>
                                    <div class="vignette vignette--tertiary ' . turnVignetteRedIfNegative(calculateRemainingBudget($dbCo, $campaign)) . '">
                                        <h4 class="vignette__ttl">Budget restant</h4>
                                        <p class="vignette__price">' . calculateRemainingBudget($dbCo, $campaign) . '</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    </li>';
            }
        }

        // Affiche la section de l'entreprise seulement s'il y a des campagnes
        if ($hasCampaigns) {
            $campaignList .= '<div class="gradient-border gradient-border--top"><h3 class="ttl secondary lineUp">' . $company['company_name'] . '</h3>';
            $campaignList .= '<ul class="campaign__grid">';
            $campaignList .= $companyCampaigns;
            $campaignList .= '</ul></div>'; // Ferme la section pour cette entreprise
        }
    }

    return $campaignList;
}


/**
 * Get HTML template for a campaign displaying most important infos. It displays campaigns by company, further subdivided by year.
 *
 * @param array $campaigns - An array containing all campaigns.
 * @param array $session - Superglobal $_SESSION.
 * @param array $companies - Tableau contenant toutes les entreprises.
 * @return string - HTML code that constitutes the template.
 */
function getHistoryCampaignTemplateByCompany(PDO $dbCo, array $campaigns, array $session, array $companies): string
{
    $campaignList = '';

    // Récupère l'année filtrée ou l'année actuelle si non définie
    $filteredYear = isset($session['filter']['year']) ? $session['filter']['year'] : null;

    foreach ($companies as $company) {
        // Démarre une section pour cette entreprise (ignore si id_company est 1)
        if ($company['id_company'] !== 1) {
            // Récupère les campagnes de l'entreprise, triées par année
            $companyCampaignsByYear = [];

            foreach ($campaigns as $campaign) {
                if ($campaign['id_company'] === $company['id_company']) {
                    // Regroupe les campagnes par année
                    $year = getYearOnly($dbCo, $campaign);

                    // Applique le filtre si une année est spécifiée
                    if ($filteredYear && $year != $filteredYear) {
                        continue; // Ignore les campagnes qui ne correspondent pas à l'année filtrée
                    }

                    $companyCampaignsByYear[$year][] = $campaign;
                }
            }

            // Vérifie si l'entreprise a des campagnes
            if (!empty($companyCampaignsByYear)) {
                $campaignList .= '<div class="gradient-border gradient-border--top"><h3 class="ttl secondary lineUp">' . $company['company_name'] . '</h3>';
                $campaignList .= '<ul class="history">';

                // Affiche les campagnes année par année
                foreach ($companyCampaignsByYear as $year => $campaignsByYear) {
                    $campaignList .= '<li class="history__year-section"><h4 class="ttl ttl--medium">Année ' . $year . '</h4>';
                    $campaignList .= '<ul class="campaign__grid">';

                    foreach ($campaignsByYear as $campaign) {
                        $campaignId = $campaign['id_campaign'];

                        $campaignList .= '
                            <li>
                                <a href="campaign.php?myc=' . $campaignId . '">
                                    <div class="card__section" data-card="">
                                        <div class="campaign__ttl">
                                            <h3 class="ttl ttl--small">' . $campaign['campaign_name'] . '</h3>'
                            . getCompanyNameIfTDC($campaign, $session) .
                            $campaign['target_com'] . '
                                        </div>
                                        <div class="campaign__stats">
                                            <div class="js-chart" id="chart-' . $campaignId . '"></div>
                                            <div class="vignettes-section">
                                                <div class="vignette vignette--primary">
                                                    <h4 class="vignette__ttl">Budget attribué</h4>
                                                    <p class="vignette__price">' . formatPrice($campaign['budget'], "€") . '</p>
                                                </div>
                                                <div class="vignette vignette--secondary">
                                                    <h4 class="vignette__ttl">Budget dépensé</h4>
                                                    <p class="vignette__price">' . calculateSpentBudget($dbCo, $campaign) . '</p>
                                                </div>
                                                <div class="vignette vignette--tertiary ' . turnVignetteRedIfNegative(calculateRemainingBudget($dbCo, $campaign)) . '">
                                                    <h4 class="vignette__ttl">Budget restant</h4>
                                                    <p class="vignette__price">' . calculateRemainingBudget($dbCo, $campaign) . '</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>';
                    }

                    $campaignList .= '</ul>';
                    $campaignList .= '</li>';
                }

                $campaignList .= '</ul>';
                $campaignList .= '</div>';
            }
        }
    }

    return $campaignList;
}


/**
 * Get HTML template for a campaign displaying most important infos. It displays campaigns by year for clients.
 *
 * @param array $campaigns - An array containing all campaigns.
 * @param array $session - Superglobal $_SESSION.
 * @param array $companies - Tableau contenant toutes les entreprises.
 * @return string - HTML code that constitutes the template.
 */
function getHistoryCampaignTemplateClient(PDO $dbCo, array $campaigns, array $session): string
{
    $campaignList = '';

    $companyCampaignsByYear = [];


    foreach ($campaigns as $campaign) {
        if ($campaign['id_company'] === $session['id_company']) {
            // Regroupe les campagnes par année
            $year = getYearOnly($dbCo, $campaign);
            $companyCampaignsByYear[$year][] = $campaign;
        }
    }

    // Vérifie si l'entreprise a des campagnes
    if (!empty($companyCampaignsByYear)) {
        $campaignList .= '<div class="gradient-border gradient-border--top">';
        $campaignList .= '<ul class="history">';

        // Affiche les campagnes année par année
        foreach ($companyCampaignsByYear as $year => $campaignsByYear) {
            // Section pour chaque année
            $campaignList .= '<li class="history__year-section"><h4 class="ttl ttl--medium">Année ' . $year . '</h4>';
            $campaignList .= '<ul class="campaign__grid">';

            foreach ($campaignsByYear as $campaign) {
                $campaignId = $campaign['id_campaign'];

                $campaignList .= '
                            <li>
                                <a href="campaign.php?myc=' . $campaignId . '">
                                    <div class="card__section" data-card="">
                                        <div class="campaign__ttl">
                                            <h3 class="ttl ttl--small">' . $campaign['campaign_name'] . ' - ' . $year . '</h3>'
                    . getCompanyNameIfTDC($campaign, $session) .
                    $campaign['target_com'] . '
                                        </div>
                                        <div class="campaign__stats">
                                            <div class="js-chart" id="chart-' . $campaignId . '"></div>
                                            <div class="vignettes-section">
                                                <div class="vignette vignette--primary">
                                                    <h4 class="vignette__ttl">Budget attribué</h4>
                                                    <p class="vignette__price">' . formatPrice($campaign['budget'], "€") . '</p>
                                                </div>
                                                <div class="vignette vignette--secondary">
                                                    <h4 class="vignette__ttl">Budget dépensé</h4>
                                                    <p class="vignette__price">' . calculateSpentBudget($dbCo, $campaign) . '</p>
                                                </div>
                                                <div class="vignette vignette--tertiary ' . turnVignetteRedIfNegative(calculateRemainingBudget($dbCo, $campaign)) . '">
                                                    <h4 class="vignette__ttl">Budget restant</h4>
                                                    <p class="vignette__price">' . calculateRemainingBudget($dbCo, $campaign) . '</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>';
            }

            $campaignList .= '</ul>'; // Ferme la liste des campagnes pour l'année
            $campaignList .= '</li>'; // Ferme la section pour l'année
        }

        $campaignList .= '</ul>'; // Ferme la liste des campagnes de l'entreprise
        $campaignList .= '</div>'; // Ferme la section pour l'entreprise
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
        $message = '';

        if (isset($_SESSION['client']) && $_SESSION['client'] === 0) {
            $message .= '
        <div class="button__section">
            <a href="new-campaign.php" class="button button--new-campaign" aria-label="Redirige vers un formulaire de création de campagne de com">Nouvelle campagne</a>
        </div>';
        }

        $message .= '
        <div class="card card__section">
            <p class="big-text">Vous n\'avez pas encore de campagnes de comm\' !</p>
        </div>
        ';


        return $message;
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
    if (!isset($campaigns['id_campaign'])) {
        // Gérer l'absence de 'id_campaign'
        return 'ID de campagne non défini';
    }

    $queryRemaining = $dbCo->prepare(
        'SELECT c.id_campaign, (c.budget - IFNULL(SUM(o.price), 0)) AS total_remaining 
        FROM campaign c
            LEFT JOIN operation o ON c.id_campaign = o.id_campaign
        WHERE c.id_campaign = :id_campaign AND c.budget > 0
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
            // Si l'utilisateur est un client
            $queryBrands = $dbCo->prepare(
                'SELECT DISTINCT b.*
                FROM brand b
                WHERE id_company = :id_company
                GROUP BY id_brand;'
            );

            $bindValues = [
                'id_company' => intval($session['id_company'])
            ];

            $queryBrands->execute($bindValues);
            $brands = $queryBrands->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Si l'utilisateur n'est pas un client, gérer les campagnes
            if (!empty($campaigns) && isset($campaigns[0]['id_campaign'])) {
                foreach ($campaigns as $campaign) {
                    $queryBrands = $dbCo->prepare(
                        'SELECT DISTINCT b.* 
                    FROM brand AS b 
                        LEFT JOIN operation_brand AS ob ON b.id_brand = ob.id_brand 
                        LEFT JOIN operation AS o ON ob.id_operation = o.id_operation 
                    WHERE o.id_campaign = :campaignId
                    GROUP BY id_brand;'
                    );

                    $bindValues = [
                        'campaignId' => intval($campaign['id_campaign'])
                    ];

                    $queryBrands->execute($bindValues);
                    $brands = array_merge($brands, $queryBrands->fetchAll(PDO::FETCH_ASSOC));
                }
            } else if (isset($campaigns['id_campaign'])) {
                // Cas où `$campaigns` n'a qu'un seul élément
                $queryBrands = $dbCo->prepare(
                    'SELECT DISTINCT b.* 
                    FROM brand AS b 
                        LEFT JOIN operation_brand AS ob ON b.id_brand = ob.id_brand 
                        LEFT JOIN operation AS o ON ob.id_operation = o.id_operation 
                    WHERE o.id_campaign = :campaignId
                    GROUP BY id_brand;'
                );

                $bindValues = [
                    'campaignId' => intval($campaigns['id_campaign'])
                ];

                $queryBrands->execute($bindValues);
                $brands = $queryBrands->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }

    return $brands;
}


/**
 * Get datas from a specific campaign.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $get - Superglobal $_GET.
 * @return array - An array containing datas from a specific campaign.
 */
function getOneCampaignDatas(PDO $dbCo, array $get): array
{
    $queryCampaigns = [];

    if (isset($get['myc'])) {
        $queryOneCampaign = $dbCo->prepare(
            'SELECT id_campaign, campaign_name, date_start, budget, c.id_user, c.id_user_TDC, firstname, lastname, co.id_company, company_name, id_target, target_com
            FROM campaign c
                JOIN users u ON c.id_user = u.id_user
                JOIN company co ON c.id_company = co.id_company
                JOIN target t USING (id_target)
            WHERE id_campaign = :id_campaign;'
        );

        $bindValues = [
            'id_campaign' => intval($get['myc'])
        ];

        $queryOneCampaign->execute($bindValues);

        return $queryOneCampaign->fetch(PDO::FETCH_ASSOC);
    }

    return $queryCampaigns;
}

/**
 * Get all operations linked to a specific campaign.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $get - Superglobal $_GET.
 * @return array - array of operations.
 */
function getCampaignOperations(PDO $dbCo, array $get): array
{
    $operations = [];

    if (isset($get['myc']) && intval($get['myc'])) {

        $queryOperations = $dbCo->prepare(
            'SELECT * 
            FROM operation 
                JOIN campaign USING (id_campaign)
                JOIN operation_brand USING (id_operation)
                JOIN brand USING (id_brand)
            WHERE id_campaign = :id_campaign
            ORDER BY operation_date DESC;'
        );

        $bindValues = [
            'id_campaign' => htmlspecialchars($get['myc'])
        ];

        $queryOperations->execute($bindValues);

        $operations = $queryOperations->fetchAll(PDO::FETCH_ASSOC);
    }

    return $operations;
}


/**
 * Get all operations of a campaign as an HTML list.
 *
 * @param array $operations - An array of operations
 * @param array $session - Superglobal $_SESSION
 * @param array $selectedCampaign - An array containing one campaign datas.
 * @return string list of campaigns.
 */
function getCampaignOperationsAsList(array $operations, array $session, array $selectedCampaign): string
{
    $operationsList = '';

    if (empty($operations)) {
        return '<p class="big-text">Pas d\'opération de communication pour cette campagne.</p>';
    }

    foreach ($operations as $operation) {
        $operationsList .= '
            <li class="operation" data-js-operation="operation"><h4 class="operation__date">' . formatFrenchDate($operation['operation_date']) . '</h4>
            <p class="campaign__operation"><span class="campaign__legend-square" style="background-color:' . $operation['legend_colour_hex'] . '"></span>' . $operation['description'] .
            ' ⮕ ' . formatPrice(floatval($operation['price']), '€') . ' H.T.';

        if (isset($session['client']) && $session['client'] === 0) {
            $operationsList .=
                '<span class="flex-row operation__row">- 
                <a class="button--edit" href="operation.php?myc=' . $selectedCampaign['id_campaign'] . '&myo=' . $operation['id_operation'] . '" title="Éditer l\'opération ' . $operation['description'] . '" aria-label="Éditer l\opération  ' . $operation['description'] . '"></a>
                 | 
                <button class="js-trash button--trash" 
        title="Supprimer l\'opération ' . $operation['description'] . ' " 
        aria-label="Supprimer l\'opération' . $operation['description'] . '" 
        id="' . $operation['id_operation'] . '"></button></span>';
        }

        $operationsList .= '</p></li>';
    }

    return $operationsList;
}


/**
 * Get all spendings by brand for a specific campaign.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $campaigns - An array containing all campaigns.
 * @return array - An array containing all spendings by brand, and by campaign.
 */
function getSpendingByBrandByCampaign(PDO $dbCo, array $campaigns, array $get): array
{
    $results = [];

    foreach ($campaigns as $campaign) {
        if (isset($campaign['id_campaign'])) {
            $querySpendingByBrand = $dbCo->prepare(
                'SELECT b.id_brand, c.id_campaign, brand_name, legend_colour_hex, SUM(o.price) AS total_spent 
                FROM brand b 
                    JOIN operation_brand ob ON b.id_brand = ob.id_brand 
                    JOIN operation o ON ob.id_operation = o.id_operation
                    JOIN campaign c ON o.id_campaign = c.id_campaign
                WHERE o.id_campaign = :id_campaign
                GROUP BY id_brand;'
            );

            $bindValues = [
                'id_campaign' => intval($get['myc'] ?? $campaign['id_campaign'])
            ];

            $querySpendingByBrand->execute($bindValues);
            $results[] = $querySpendingByBrand->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $results[] = ['error' => 'id_campaign manquant pour une campagne.'];
        }
    }

    return $results;
}


// function filterCampaigns(PDO $dbCo, array $campaigns)
// {
//     if (!isset($_POST['date-from'], $_POST['date-to'])) {
//         addError('>ko');
//         redirectTo();
//         exit;
//     }

//     $dateFrom = sanitizeInput($_POST['date-from']);
//     $dateTo = sanitizeInput($_POST['date-to']);

//     $queryFilter = $dbCo->prepare(
//         'SELECT * 
//         FROM campaigns 
//         WHERE date 
//         BETWEEN :dateFrom AND :dateTo;'
//     );

//     $bindValues = [
//         'dateFrom' => $dateFrom,
//         'dateTo' => $dateTo
//     ];

//     $queryFilter->execute($bindValues);

//     $campaigns = $queryFilter->fetchAll();

//     echo json_encode($campaigns);
// }

/**
 * Fetch all 3 objectives for a communication campaign.
 *
 * @param PDO $dbCo - Connection to database.
 * @return array - An array containing all 3 objectives for a communication campaign.
 */
function fetchCampaignTarget(PDO $dbCo): array
{
    $queryTarget = $dbCo->query('SELECT * FROM target');

    return $queryTarget->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * get all 3 objectives for a communication campaign as HTML options.
 *
 * @param array $targets - An array containing all 3 objectives for a communication campaign.
 * @return string - HTML options for objectives.
 */
function getTargetsAsHTMLOptions(array $targets): string
{
    $options = '<option value="">- Sélectionner un objectif -</option>';

    foreach ($targets as $target) {
        $options .= '<option value="' . $target['id_target'] . '">' . $target['target_com'] . '</option>';
    }

    return $options;
}

/**
 * Get company campaigns for current year.
 *
 * @param PDO $dbCo - PDO connection object
 * @param array $session - Superglobal $_SESSION.
 * @return array - array of campaigns for current year for a specific company.
 */
function getOneCompanyYearlyCampaigns(PDO $dbCo, array $session): array
{
    $query = $dbCo->prepare(
        'SELECT *
        FROM campaign
        WHERE id_company = :idcompany AND YEAR(date_start) = YEAR(CURDATE());'
    );

    if (isset($session['id_company']) && $session['id_company'] !== 1) {
        $bindValues = [
            'idcompany' => $session['id_company']
        ];
    } else if (isset($session['filter']['id_company'])) {
        $bindValues = [
            'idcompany' => $session['filter']['id_company']
        ];
    } else if (isset($session['client']) && $session['client'] === 0) {
        $bindValues = [
            'idcompany' => $session['id_company']
        ];
    } else {
        return [];
    }

    $query->execute($bindValues);

    return $query->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Display a button that is a form to delete a campaign with actions.php. I needed it to redirect so I did not wanted it to be AJAX.
 *
 * @param array $selectedCampaign - The array containing the selected campaign datas.
 * @param array $session - Superglobal $_SESSION.
 * @return string - A "form" to delete a campaign that only has a button.
 */
function deleteCampaignButton(array $selectedCampaign, array $session): string
{
    return '
        <form method="post" action="actions-campaign.php" onsubmit="return confirmDelete();">
            <button type="submit" value="" class="button--trash" aria-label="Supprimer l\'opération ' . $selectedCampaign['campaign_name'] . '">
            <input type="hidden" name="token" value="' . $session['token'] . '">
            <input type="hidden" name="action" value="delete-campaign">
            <input type="hidden" name="id_campaign" value="' . $selectedCampaign['id_campaign'] . '">
        </form>';
}


/**
 * Calculates spent budget for the specified year (filters)
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $session - Superglobal $_SESSION
 * @return void 
 */
function calculateHistorySpentBudget(PDO $dbCo, array $session)
{
    $query = $dbCo->prepare(
        'SELECT SUM(price) as total_spent
        FROM operation
        WHERE id_company = :id_company AND YEAR(operation_date) = :year;'
    );

    $bindValues = [
        'id_company' => intval($session['filter']['id_company']),
        'year' => intval($session['filter']['year'])
    ];

    $query->execute($bindValues);

    $result = $query->fetch();

    return formatPrice(floatval($result['total_spent'] ?? 0), '€');
}
