<?php

/**
 * Fetch all partners's datas from database.
 *
 * @param PDO $dbCo - Connection to database.
 * @return array - Array of partners.
 */
function fetchAllPartners(PDO $dbCo): array
{
    $query = $dbCo->query('SELECT * FROM partner ORDER BY partner_name ASC;');

    $partners = $query->fetchAll(PDO::FETCH_ASSOC);

    return $partners;
}


/**
 * Get all partners as HTML options for a select input.
 *
 * @param array $partners - Array of partners datas.
 * @return string - HTML options.
 */
function getPartnersAsHTMLOptions(array $partners, array $operation, array $get): string
{
    $htmlOptions = '<option value="">- Pas de partenaire -</option>';

    foreach ($partners as $partner) {
        $htmlOptions .= '<option class="form__input--select-option" value="' . intval($partner['id_partner']) . '"';

        if (isset($get['myo']) && $partner['id_partner'] === $operation['id_partner']) {
            $htmlOptions .= ' selected';
        }

        $htmlOptions .= '>' . $partner['partner_name'] . '</option>';
    }

    return $htmlOptions;
}


/**
 * Get annual budget spent by partner for each company.
 *
 * @param PDO $dbCo - PDO connection.
 * @param array $session - Superglobal $_SESSION.
 * @return array - An array containing all datas from partner per company
 */
function getAnnualBudgetPerPartnerPerCompany(PDO $dbCo, array $session, array $get): array
{
    if (isset($session['filter']['id_company']) && isset($get['client'])) {
        $query = $dbCo->prepare(
            'SELECT partner.id_partner, partner_name, partner_colour, SUM(price) AS annual_spendings, YEAR(operation_date) AS year
        FROM partner
            JOIN operation ON partner.id_partner = operation.id_partner
        WHERE YEAR(operation_date) = YEAR(CURDATE()) AND id_company = :id_company
        GROUP BY partner.id_partner, year;'
        );

        $bindValues = [
            'id_company' => intval($session['filter']['id_company']) ? $get['client'] : null
        ];

        $query->execute($bindValues);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    return [];
}


/**
 * Get annual budget spent by partner.
 *
 * @param PDO $dbCo - PDO connection.
 * @param array $session - Superglobal $_SESSION.
 * @return array - An array containing all datas from partner per company
 */
function getAnnualBudgetPerPartner(PDO $dbCo): array
{
    $query = $dbCo->query(
        'SELECT partner.id_partner, partner_name, partner_colour, SUM(price) AS annual_spendings, YEAR(operation_date) AS year
        FROM partner
            JOIN operation ON partner.id_partner = operation.id_partner
        WHERE YEAR(operation_date) = YEAR(CURDATE())
        GROUP BY partner.id_partner, year;'
    );

    return $query->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get annual budget spent by partner for a campaign.
 *
 * @param PDO $dbCo - PDO connection.
 * @param array $session - Superglobal $_SESSION.
 * @return array - An array containing all datas from partner per company
 */
function getCampaignBudgetPerPartner(PDO $dbCo, array $selectedCampaign, array $get): array
{
    if (isset($get['myc'])) {
        $query = $dbCo->prepare(
            'SELECT partner.id_partner, partner_name, partner_colour, SUM(price) AS annual_spendings, campaign.id_campaign
            FROM partner
                JOIN operation ON partner.id_partner = operation.id_partner
                JOIN campaign ON operation.id_campaign = campaign.id_campaign
            WHERE YEAR(operation_date) = YEAR(CURDATE()) AND campaign.id_campaign = :id_campaign
            GROUP BY partner.id_partner, id_campaign;'
        );

        $bindValues = [
            'id_campaign' => intval($selectedCampaign['id_campaign'])
        ];

        $query->execute($bindValues);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    return [];
}

/**
 * Generate a table for partner spendings.
 *
 * @param array $partnerAnnualSpendings - Partner array
 * @return string - A table containing all datas.
 */
function generatePartnerTable(array $partnerAnnualSpendings): string
{
    $htmlTable = '<table class="table">';

    $htmlTable .= '<thead><tr><th class="table__head">Partenaire</th><th class="table__head">Dépenses H.T.</th></tr></thead>';
    $htmlTable .= '<tbody>';

    foreach ($partnerAnnualSpendings as $partner) {
        $htmlTable .= '<tr>';
        $htmlTable .= '<td class="table__cell" aria-label="Cellule du partenaire ' . $partner['partner_name'] . '"><span class="campaign__legend-square campaign__legend-square--long" style="background-color:' . $partner['partner_colour'] . '"></span>' . htmlspecialchars($partner['partner_name']) . '</td>';
        $htmlTable .= '<td class="table__cell" aria-label="Cellule de dépenses pour le partenaire ' . $partner['partner_name'] . '">' . formatPrice($partner['annual_spendings'], '€') . '</td>';
        $htmlTable .= '</tr>';
    }

    $htmlTable .= '</tbody>';
    $htmlTable .= '</table>';

    return $htmlTable;
}
