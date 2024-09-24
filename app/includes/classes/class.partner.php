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
function getPartnersAsHTMLOptions(array $partners): string
{
    $htmlOptions = '<option value="0">- Sélectionner un partenaire -</option>';

    foreach ($partners as $partner) {
        $htmlOptions .= '<option class="form__input--select-option" value="' . intval($partner['id_partner']) . '">' . $partner['partner_name'] . '</option>';
    }

    return $htmlOptions;
}
