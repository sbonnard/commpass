<?php

/**
 * Fetch all brands from a company.
 *
 * @param PDO $dbCo - Connection to the database.
 * @return array - Array of brands.
 */
function fetchCompanyBrands(PDO $dbCo, array $session):array
{
    $query = $dbCo->prepare(
        'SELECT id_brand, brand_name, legend_colour_hex, id_company
        FROM brand
        WHERE id_company = :id_company;'
    );

    $bindValues = [
        'id_company' => $session['id_company']
    ];

    $query->execute($bindValues);

    return $query->fetchAll(PDO::FETCH_ASSOC);
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


/**
 * Get all brands from a company by comparing company_name.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $get - Superglobal $_GET.
 * @return array $brandList - list of brand
 */
function getCompanyBrands(PDO $dbCo, array $selectedCampaign): array
{
    if (!isset($selectedCampaign['id_company'])) {
        return []; // Return empty array if no company id is provided. 
    }

    $queryBrands = $dbCo->prepare(
        'SELECT id_brand, brand_name, legend_colour_hex, company.id_company
        FROM brand
            JOIN company ON company.id_company = brand.id_company
        WHERE company.id_company = :id_company
        ORDER BY brand_name ASC;'
    );

    $bindValues = [
        'id_company' => $selectedCampaign['id_company']
    ];

    $queryBrands->execute($bindValues);

    $brandList = $queryBrands->fetchAll(PDO::FETCH_ASSOC);

    return $brandList;
}

/**
 * Get all brands from a company as HTML options.
 *
 * @param array $companyBrands - list of brand
 * @return string - A list of brand names as HTML options.
 */
function getCompanyBrandsAsHTMLOptions(array $companyBrands): string
{
    $brandOptions = '';

    foreach ($companyBrands as $brand) {
        $brandOptions .= '<option data-color="' . $brand['legend_colour_hex'] . '" value="' . $brand['id_brand'] . '">' . $brand['brand_name'] . '</option>';
    }

    return $brandOptions;
}
