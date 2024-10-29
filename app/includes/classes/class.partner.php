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
function getAnnualBudgetPerPartnerPerCompany(PDO $dbCo, array $session): array
{
    $query = $dbCo->prepare(
        'SELECT partner.id_partner, partner_name, SUM(price) AS annual_spendings, YEAR(operation_date) AS year
        FROM partner
            JOIN operation ON partner.id_partner = operation.id_partner
        WHERE YEAR(operation_date) = YEAR(CURDATE()) AND id_company = :id_company
        GROUP BY partner.id_partner, year;'
    );

    $bindValues = [
        'id_company' => intval($session['filter']['id_company'])
    ];

    $query->execute($bindValues);

    return $query->fetchAll();
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
        'SELECT partner.id_partner, partner_name, SUM(price) AS annual_spendings, YEAR(operation_date) AS year
        FROM partner
            JOIN operation ON partner.id_partner = operation.id_partner
        WHERE YEAR(operation_date) = YEAR(CURDATE())
        GROUP BY partner.id_partner, year;'
    );

    $query->execute();

    return $query->fetchAll();
}

function generatePartnerTable(array $partnerAnnualSpendings)
{
    $htmlTable = '<table class="table">';

    $htmlTable .= '<thead><tr><th class="table__head">Partenaire</th><th class="table__head">Dépenses H.T.</th></tr></thead>';
    $htmlTable .= '<tbody>';

    foreach ($partnerAnnualSpendings as $partner) {
        $htmlTable .= '<tr>';
        $htmlTable .= '<td class="table__cell" aria-label="Cellule du partenaire ' . $partner['partner_name'] . '">' . htmlspecialchars($partner['partner_name']) . '</td>';
        $htmlTable .= '<td class="table__cell" aria-label="Cellule de dépenses pour le partenaire ' . $partner['partner_name'] . '">' . formatPrice($partner['annual_spendings'], '€') . '</td>';
        $htmlTable .= '</tr>';
    }

    $htmlTable .= '</tbody>';
    $htmlTable .= '</table>';

    return $htmlTable;
}
