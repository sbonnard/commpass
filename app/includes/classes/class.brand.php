<?php

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
    if(!isset($selectedCampaign['id_company'])) {
        return []; // Return empty array if no company id is provided. 
    }
    
    $queryBrands = $dbCo->prepare(
        'SELECT id_brand, brand_name, legend_colour_hex, company.id_company
        FROM brand
            JOIN company ON company.id_company = brand.id_company
        WHERE company.id_company = :id_company;'
    );

    $bindValues = [
        'id_company' => $selectedCampaign['id_company']
    ];

    $queryBrands->execute($bindValues);

    $brandList = $queryBrands->fetchAll(PDO::FETCH_ASSOC);

    return $brandList;
}


function getCompanyBrandsAsHTMLOptions(array $companyBrands): string {
    $brandOptions = '';

    $brandOptions .= '<option value="0">Toutes les marques</option>';

    foreach ($companyBrands as $brand) {
        $brandOptions.= '<option value="'. $brand['id_brand']. '">'. $brand['brand_name']. '</option>';
    }

    return $brandOptions;
}


