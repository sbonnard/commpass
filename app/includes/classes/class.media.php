<?php

/**
 * Fetch all medias from database.
 *
 * @param PDO $dbCo - Connection to database.
 * @return array - Array of media datas.
 */
function fetchAllMedia(PDO $dbCo): array
{
    $queryMedia = $dbCo->query('SELECT * FROM media ORDER BY media_name ASC;');

    $mediaDatas = $queryMedia->fetchAll(PDO::FETCH_ASSOC);

    return $mediaDatas;
}


/**
 * Get all media as HTML options for a select input.
 *
 * @param array $mediaDatas - Array of media datas.
 * @return string - HTML options.
 */
function getMediaAsHTMLOptions(array $mediaDatas): string
{
    $mediaHTML = '<option value="0">- Sélectionner un média -</option>';

    foreach ($mediaDatas as $media) {
        $mediaHTML .= '<option class="form__input--select-option" value="' . $media['id_media'] . '">' . $media['media_name'] . '</option>';
    }

    return $mediaHTML;
}
