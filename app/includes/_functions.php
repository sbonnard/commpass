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
function getDatasAsHTMLOptions(array $datas, string $placeholder, string $id, string $dataName, string $dataNameBis = ''): string
{
    $htmlOptions = '<option class="form__input__placeholder" value="">- ' . htmlspecialchars($placeholder) . ' -</option>';

    foreach ($datas as $data) {
        // I check here if datas exist to avoid a PHP error.
        $dataNameBisValue = !empty($dataNameBis) ? ' ' . htmlspecialchars($data[$dataNameBis]) : '';

        $htmlOptions .=
            '<option value="' . htmlspecialchars($data[$id]) . '">' .
            htmlspecialchars($data[$dataName]) . $dataNameBisValue .
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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// FUNCTIONS TO FORMAT DATES


/**
 * Format a month and year into a readable French format. Example : '2024-12' -> 'Décembre 2024'.
 *
 * @param string $yearAndMonth - The year and month to format.
 * @return string - The formatted date string.
 */
function formatFrenchDate(string $yearMonthDay): string
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
 * Get year only from a campaign.
 *
 * @param PDO $dbCo - Connection to database.
 * @param array $campaign - Campaign array
 * @return string - The year from the campaign.
 */
function getYearOnly(PDO $dbCo, array $campaign): string
{
    $queryYear = $dbCo->prepare(
        'SELECT YEAR(date_start) AS year
        FROM campaign
        WHERE id_campaign = :id_campaign;'
    );

    $bindValues = [
        'id_campaign' => intval($campaign['id_campaign'])
    ];

    $queryYear->execute($bindValues);

    $result = $queryYear->fetch(PDO::FETCH_ASSOC);

    return implode('', $result);
}

/**
 * Formats date to get it back when editing.
 *
 * @param string $date - The date to format
 * @return string - The formatted date
 */
function formatDateForInput(string $date): string
{
    $dateTime = new DateTime($date);
    return $dateTime->format('Y-m-d');
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// FUNCTIONS TO FORMAT PHONE NUMBERS


/**
 * Formats phone numbers from database. Example : 0601020304 -> 06 01 02 03 04.
 *
 * @param string $phoneNumber - The phone number to format.
 * @return string - The formatted phone number.
 */
function formatPhoneNumber(string $phoneNumber): string
{
    // Vérifie si le numéro de téléphone a bien 10 chiffres
    if (strlen($phoneNumber) === 10) {
        // Utilise une expression régulière pour insérer un espace toutes les deux positions
        return preg_replace('/(\d{2})(?=\d)/', '$1 ', $phoneNumber);
    }
    // Retourne le numéro original s'il ne correspond pas au format attendu
    return $phoneNumber;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// FUNCTIONS TO GET DATAS AS A TABLE 

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


/**
 * Displays a button to create a new budget if the user is not a client.
 *
 * @param array $session - Superglobal session.
 * @return string - The new budget button.
 */
function displayButtonIfNotClient(array $session, string $getValue = ""): string
{
    if (isset($session['client']) && $session['client'] === 0) {
        return '<a href="/new-budget' . $getValue . '" class="button--setting" aria-label="Redirige vers un formulaire de création de budget" title="Paramétrer le budget"></a>';
    } else {
        return '';
    }
}


/**
 * Turns a vignette red if the company's remaining budget is negative.
 *
 * @param string $companyRemainings - The remaining budget of the company.
 * @return string - The class name for the vignette. If the budget is negative, it returns 'vignette--negative'. Otherwise, it returns an empty string.
 */
function turnVignetteRedIfNegative(string $companyRemainings): string
{
    // Supprime tous les caractères non numériques sauf les signes - ou . pour la conversion
    $cleanedValue = preg_replace('/[^\d.-]/', '', $companyRemainings);
    $className = '';

    if (floatval($cleanedValue) < 0) {
        $className = 'vignette--negative';
    }

    return $className;
}


/**
 * Unset filters if set.
 *
 * @param array $session - Superglobal $_SESSION.
 * @return void
 */
function unsetFilters(array $session)
{
    if (isset($session['filter'])) {
        unset($session['filter']);
    }
}


/**
 * Permet d'obtenir une date au format français. Exemple : 2024-01-01 -> 1 Janvier 2024.
 *
 * @param [type] $date - La date à convertir.
 * @return string - La date convertie en chaîne de caractères.
 */
function getDateText($date): string
{
    $jour = getdate(strtotime($date));
    $semaine = array(
        " Dimanche ",
        " Lundi ",
        " Mardi ",
        " Mercredi ",
        " Jeudi ",
        " vendredi ",
        " samedi "
    );
    $mois = array(
        1 => " Janvier ",
        " Février ",
        " Mars ",
        " Avril ",
        " Mai ",
        " Juin ",
        " Juillet ",
        " Août ",
        " Septembre ",
        " Octobre ",
        " Novembre ",
        " Décembre "
    );
    return /*$semaine[$jour['wday']] . */ $jour['mday'] . $mois[$jour['mon']] . $jour['year'];
}


/**
 * Get a message if history is empty.
 *
 * @param array $history - A list of history
 * @return string - The message to display.
 */
function getMessageIfNoHistory(array $history, array $session): string
{
    if (empty($history) && isset($session['filter']['year'])) {

        return
            '
                <div class="card">
                    <section class="card__section">
                        <p class="big-text">Pas d\'historique sur l\'année ' . $session['filter']['year'] . '.</p>
                    </section>
                </div>
                ';
    }

    return '<div class="card">
                <section class="card__section">
                    <p class="big-text">Pas d\'historique disponible.</p>
                </section>
            </div>';
}
