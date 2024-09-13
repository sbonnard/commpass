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
