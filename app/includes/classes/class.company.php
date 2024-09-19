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


function getCompanyNameForNewOp(PDO $dbCo, array $get)
{
    if (isset($get['myc'])) {
        $sql = 'SELECT company_name
            FROM company
                JOIN operation ON company.id_company = operation.id_company
            WHERE id_campaign = :myc';

        if (isset($get['myc'], $get['myo'])) {
            $sql .= ' AND id_operation = :myo';
        }

        $query = $dbCo->prepare($sql);

        $bindValues = ['myc' => intval($get['myc'])];

        if (isset($get['myo'])) {
            $bindValues['myo'] = intval($get['myo']);
        }

        $query->execute($bindValues);

        $companyDatas = $query->fetch(PDO::FETCH_ASSOC);

        if (!$companyDatas) {
            return null;
        }

        return $companyDatas['company_name'];
    }
}
