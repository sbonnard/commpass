<?php

global $dbCo;

/**
 * Redirect to the given URL or to the previous page if no URL is provided.
 *
 * @param string|null $url URL to redirect to. If null, redirect to the previous page.
 * @return void
 */
function redirectTo(?string $url = null): void
{
    if ($url === null) {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
        } else {
            $url = 'defaultPage.php'; // Fallback URL if HTTP_REFERER is not set
        }
    }
    header('Location: ' . $url);
    exit;
}


/**
 * Get options for whatever datas you set as parameters.
 *
 * @param array $datas - The array containing the datas.
 * @param string $id - The id field in the array.
 * @param string $dataName - The name of the the interlocutor or company.
 * @return string - The HTML options for the select field.
 */
function getDatasAsHTMLOptions(array $datas, string $placeholder, string $id, string $dataName): string
{
    $htmlOptions = '<option class="form__input__placeholder" value="">- ' . $placeholder . ' -</option>';

    foreach ($datas as $data) {
        $htmlOptions .=
            '<option value="' . htmlspecialchars($data[$id], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($data[$dataName], ENT_QUOTES, 'UTF-8') .
            '</option>';
    }

    return $htmlOptions;
}


/**
 * Formats a price in a specific way. Example : 25000.00 -> 25 000 € OR 18000.50 -> 18 000,50 €.
 *
 * @param float $price - The price to format.
 * @param string $currency - The currency you want to apply to the price.
 * @return string - The price formated.
 */
function formatPrice(float|int $price, string $currency): string
{
    if ($price == (int)$price) {
        return number_format($price, 0, ',', ' ') . ' ' . $currency;
    } else {
        return number_format($price, 2, ',', ' ') . ' ' . $currency;
    }
}


/**
 * Sanitize input data to prevent XSS attacks. Remove any potentially harmful characters and escape HTML special characters.
 *
 * @param string $input - The input string to sanitize.
 * @return string - The sanitized input string.
 */
function sanitizeInput(string $input): string
{
    return trim(htmlspecialchars($input, ENT_QUOTES, 'UTF-8'));
}

/**
 * Format a month and year into a readable French format. Example : '2024-12' -> 'Décembre 2024'.
 *
 * @param string $yearAndMonth - The year and month to format.
 * @return string - The formatted date string.
 */
function formatDate(string $yearMonthDay): string
{
    [$year, $month, $day] = explode('-', $yearMonthDay);

    $months = [
        '01' => 'Janvier',
        '02' => 'Février',
        '03' => 'Mars',
        '04' => 'Avril',
        '05' => 'Mai',
        '06' => 'Juin',
        '07' => 'Juillet',
        '08' => 'Août',
        '09' => 'Septembre',
        '10' => 'Octobre',
        '11' => 'Novembre',
        '12' => 'Décembre'
    ];

    $monthName = $months[$month] ?? 'Mois inconnu';
    return $day . ' ' . $monthName . ' ' . $year;
}

/**
 * Generates a table from operations datas and brands.
 *
 * @param array $brandsSpendings - The results from operations.
 * @return string - The generated table
 */
function generateTableFromDatas(array $brandsSpendings): string
{
    $htmlTable = '<table class="table">';

    $htmlTable .= '<thead><tr><th class="table__head">Marque</th><th class="table__head">Dépenses H.T.</th></tr></thead>';
    $htmlTable .= '<tbody>';

    foreach ($brandsSpendings as $brand) {
        $htmlTable .= '<tr>';
        $htmlTable .= '<td class="table__cell" aria-label="Cellule de la marque ' . $brand['brand_name'] . '"><span class="campaign__legend-square campaign__legend-square--long" style="background-color:' . $brand['legend_colour_hex'] . '"></span>' . htmlspecialchars($brand['brand_name']) . '</td>';
        $htmlTable .= '<td class="table__cell" aria-label="Cellule de dépenses pour la marque ' . $brand['brand_name'] . '">' . formatPrice($brand['total_spent'], '€') . '</td>';
        $htmlTable .= '</tr>';
    }

    $htmlTable .= '</tbody>';
    $htmlTable .= '</table>';

    return $htmlTable;
}


/**
 * Merge results from multiple operations into a single array.
 *
 * @param array $campaignResults - The results from multiple operations.
 * @return array The merged results.
 */
function mergeResults(array $campaignResults): array
{
    // Retourner uniquement les résultats de la première campagne
    if (!empty($campaignResults)) {
        return $campaignResults[0];
    }

    return [];
}