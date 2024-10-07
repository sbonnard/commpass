<?php

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
                ORDER BY company_name ASC;'
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


function getClientName(PDO $dbCo, array $session, array $selectedCampaign = []): string
{
    $query = $dbCo->prepare(
        'SELECT company_name
        FROM company
        WHERE id_company = :id;'
    );

    if (isset($session['filter']['id_company'])) {
        $bindValues = [
            'id' => intval($session['filter']['id_company'])
        ];
    } else {
        $bindValues = [
            'id' => intval($selectedCampaign['id_company'])
        ];
    }
    $query->execute($bindValues);

    $companyDatas = $query->fetch();

    return implode($companyDatas);
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


function getCompanyNameForNewBrand(PDO $dbCo, array $get)
{
    if (isset($get['myc'])) {
        $sql = 'SELECT company_name
            FROM company
                JOIN operation ON company.id_company = operation.id_company
            WHERE id_company =:id_company';

        $query = $dbCo->prepare($sql);

        $bindValues = ['id_company' => intval($get['comp'])];

        $query->execute($bindValues);

        $companyDatas = $query->fetch(PDO::FETCH_ASSOC);

        if (!$companyDatas) {
            return null;
        }

        return $companyDatas['company_name'];
    }
}


/**
 * get companies as HTML options.
 *
 * @param array $targets - An array containing clients.
 * @return string - HTML options for clients.
 */
function getCompaniesAsHTMLOptions(array $companies): string
{
    $options = '<option value="">- Sélectionner un client -</option>';

    foreach ($companies as $company) {
        $options .= '<option value="' . $company['id_company'] . '">' . $company['company_name'] . '</option>';
    }

    return $options;
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// BUDGET CALCULATIONS 

/**
 * Fetch annual budget of a company from the database.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $session - Superglobal $_SESSION.
 * @param array $get - Superglobal $_GET.
 * @return string - Returns the annual budget of the company.
 */
function fetchCompanyAnnualBudget(PDO $dbCo, array $session): string
{
    $query = $dbCo->prepare(
        'SELECT annual_budget
        FROM company
            JOIN budgets ON budgets.id_company = company.id_company
        WHERE budgets.id_company = :id_company;'
    );

    if (isset($session['id_company']) && $session['id_company'] !== 1) {
        $bindValues = ['id_company' => intval($session['id_company'])];
    } else if (isset($session['filter']) && isset($session['filter']['id_company']) && $session['filter']['id_company'] !== 1) {
        $bindValues = ['id_company' => intval($session['filter']['id_company'])];
    } else {
        $bindValues = [
            'id_company' => 1 // default for admin
        ];
    }

    $query->execute($bindValues);

    $annualBudget = $query->fetch(PDO::FETCH_ASSOC);

    return $annualBudget ? implode('', $annualBudget) : 0;
}


/**
 * Calculates the annual spent budget of a company from the database.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $session - Superglobal $_SESSION.
 * @param array $get - Superglobal $_GET.
 * @return string - Returns the annual spent budget of the company.
 */
function calculateAnnualSpentBudget(PDO $dbCo, array $session): string
{
    $query = $dbCo->prepare(
        'SELECT SUM(price) AS total_spent
        FROM operation
        WHERE YEAR(operation_date) = YEAR(CURDATE()) AND id_company = :id_company
        GROUP BY id_company;'
    );

    if (isset($session['id_company']) && $session['id_company'] !== 1) {
        $bindValues = ['id_company' => intval($session['id_company'])];
    } else if (
        isset($session['id_company'])
        && $session['id_company'] === 1
        && isset($session['filter']['id_company'])
        && $session['filter']['id_company'] !== 1
    ) {
        $bindValues = ['id_company' => intval($session['filter']['id_company'])];
    } else {
        $bindValues = ['id_company' => 1];
    }

    $query->execute($bindValues);

    $spentBudget = $query->fetch(PDO::FETCH_ASSOC);

    // Gestion des cas où aucune dépense n'est trouvée
    return $spentBudget ? $spentBudget['total_spent'] : 0;
}



/**
 * Calculates the annual remaining budget of a company from the database.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $session - Superglobal $_SESSION.
 * @return string - Returns the annual remaining budget of the company.
 */
function calculateAnnualRemainingBudget(PDO $dbCo, array $session): string
{
    $query = $dbCo->prepare(
        'SELECT annual_budget - SUM(price) AS remaining_budget
        FROM company
            JOIN budgets ON company.id_company = budgets.id_company
            JOIN operation ON company.id_company = operation.id_company
        WHERE YEAR(operation_date) = YEAR(CURDATE()) AND company.id_company = :id_company
        GROUP BY budgets.id_budget;'
    );

    if (isset($session['id_company']) && $session['id_company'] !== 1) {
        $bindValues = ['id_company' => intval($session['id_company'])];
    } else if (isset($session['id_company']) && $session['id_company'] === 1 && isset($session['filter']['id_company']) &&  $session['filter']['id_company'] !== 1) {
        $bindValues = ['id_company' => intval($session['filter']['id_company'])];
    } else {
        $bindValues = ['id_company' => 1]; // default
    }

    $query->execute($bindValues);

    $remainingBudget = $query->fetch(PDO::FETCH_ASSOC);

    return $remainingBudget ? $remainingBudget['remaining_budget'] : 0;
}


/**
 * Get annual spendings grouped by communication campaign objectives.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $session - Superglobal $_SESSION.
 * @return array - An array containing the annual spendings grouped by communication campaign objectives.
 */
function getAnnualSpendingsByTarget(PDO $dbCo, array $session): array
{
    $query = $dbCo->prepare(
        'SELECT c.id_company, c.id_target, t.target_com AS target, target_legend_hex, SUM(o.price) AS total, YEAR(c.date_start) AS year 
        FROM campaign c 
            JOIN operation o ON c.id_campaign = o.id_campaign 
            JOIN target t ON c.id_target = t.id_target
        WHERE c.id_company = :id_company 
        GROUP BY c.id_company, c.id_target, year 
        HAVING year = YEAR(CURDATE());'
    );

    if (!isset($session['filter']) && !isset($session['filter']['id_company'])) {
        $bindValues = [
            'id_company' => intval($session['id_company'])
        ];
    } else if (isset($session['filter']) && isset($session['filter']['id_company'])) {
        $bindValues = [
            'id_company' => intval($session['filter']['id_company'])
        ];
    }

    $query->execute($bindValues);

    return $query->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Generates a table from objective datas (objective) .
 *
 * @param array $target - An array containing communication objective data.
 * @return string - The generated table
 */
function generateTableFromTargetDatas(array $targetSpendings): string
{
    $htmlTable = '<table class="table">';

    $htmlTable .= '<thead><tr><th class="table__head">Objectif</th><th class="table__head">Dépenses H.T.</th></tr></thead>';
    $htmlTable .= '<tbody>';

    foreach ($targetSpendings as $target) {
        $htmlTable .= '<tr>';
        $htmlTable .= '<td class="table__cell" aria-label="Cellule de l\'objectif ' . $target['target'] . '"><span class="campaign__legend-square campaign__legend-square--long" style="background-color:' . $target['target_legend_hex'] . '"></span>' . htmlspecialchars($target['target']) . '</td>';
        $htmlTable .= '<td class="table__cell" aria-label="Cellule de dépenses pour l\'objectif' . $target['target'] . '">' . formatPrice($target['total'], '€') . '</td>';
        $htmlTable .= '</tr>';
    }

    $htmlTable .= '</tbody>';
    $htmlTable .= '</table>';

    return $htmlTable;
}

/**
 * Fetch annual budget of a company if a filter is applied
 *
 * @param PDO $dbCo - the PDO connection
 * @param [type] $year - the year of the filter applied.
 * @return void
 */
function fetchAnnualBudgetPerYearPerCompany(PDO $dbCo, array $session)
{

    if (isset($session['filter']['year'])) {
        $query = $dbCo->prepare(
            'SELECT annual_budget
        FROM budgets
        WHERE id_company = :id_company AND year = :year;'
        );

        if (isset($session['client']) && $session['client'] === 1) {
            $bindValues = [
                'id_company' => $session['id_company'],
                'year' => date('Y')
            ];
        } else if (isset($session['client']) && $session['client'] === 0 && isset($session['filter']['id_company']) && isset($session['filter']['year'])) {
            $bindValues = [
                'id_company' => $session['filter']['id_company'],
                'year' => $session['filter']['year']
            ];
        }

        $query->execute($bindValues);

        $result = $query->fetch();

        if ($result === false) {
            return 0;
        }

        return implode('', $result);
    }
}

/**
 * Fetch history campaign per year if filter is applied.
 *
 * @param PDO $dbCo - DB connection
 * @param array $session - Superglobal session
 */
function getOneCompanyDatasFilteredHistory(PDO $dbCo, array $session)
{
    if (isset($session['filter']['year'])) {
        $query = $dbCo->prepare(
            'SELECT id_campaign, campaign_name, budget, date_start, date_end, id_user, id_user_TDC, 
        target.id_target, target_com,
        company.id_company, company_name,
        year, annual_budget
        FROM campaign
            JOIN target USING (id_target)
            JOIN company USING (id_company)
            JOIN budgets USING(id_company)
        WHERE id_company = :id_company AND YEAR(date_start) = :year;'
        );

        if (isset($session['filter']['id_company'])) {
            $bindValues = [
                'id_company' => $session['filter']['id_company'],
                'year' => $session['filter']['year']
            ];
        } else if (isset($session['client']) && $session['client'] === 1) {
            $bindValues = [
                'id_company' => $session['id_company'],
                'year' => $session['filter']['year']
            ];
        }

        $query->execute($bindValues);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    return []; // if no filter is applied
}
