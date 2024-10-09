<?php

/**
 * Template for head.
 *
 * @param string $title - A title for your head.
 * @return string - Footer's content, HTML elements
 */
function fetchHead(string $title): string
{
    return '
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Commpass, le CRM spécialisé dans la gestion des budgets de communication, vous aide à créer des comptes-rendus clients clairs et performants. Simplifiez la gestion de vos campagnes.">
    <title>' . $title . '</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <!-- if development -->
    <!-- <script type="module" src="http://localhost:5173/@vite/client"></script> -->
    <!-- <script type="module" src="http://localhost:5173/js/script.js"></script> -->

    <!-- Production -->
    <link rel="stylesheet" href="assets/assets/script-CxvkZjnF.css">
    <script type="module" src="assets/assets/script-CsENuWDK.js"></script>

    <!-- C3 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.css" />

    <!-- D3 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/5.16.0/d3.min.js"></script>

    <!-- C3 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.js"></script>';
}


function automatizeScriptsForDeploy()
{
    $manifest = json_decode(file_get_contents(__DIR__ . '/../assets/.vite/manifest.json'), true);

    // Injecter les CSS
    if (isset($manifest['js/script.js']['css'])) {
        foreach ($manifest['js/script.js']['css'] as $cssFile) {
            echo '<link rel="stylesheet" href="/assets/' . $cssFile . '">';
        }
    }

    // Injecter les JS
    echo '<script type="module" src="/assets/' . $manifest['js/script.js']['file'] . '"></script>';
}


/**
 * Automatize font for deployment reading assets/manifest.json file.
 *
 * @return string - A string that defines fonts.
 */
function automatizeFontsForDeploy(): string
{
    $manifest = json_decode(file_get_contents(__DIR__ . '/assets/.vite/manifest.json'), true);

    // Exemple pour les polices
    $fontJura = $manifest['assets/assets/jura-oeZM-I3y.woff2']['file'];
    $fontRoboto = $manifest['assets/assets/roboto-regular-webfont-DmTtHm-J.woff']['file'];
    $fontRobotoBold = $manifest['assets/assets/roboto-bold-webfont-DLEC0mPx.woff']['file'];

    return
        '<style>
@font-face {
    font-family: \'jura\';
    src: url(\'/assets/' . $fontJura . ') format(\'woff2\');
    font-weight: normal;
    font-style: normal;
}

@font-face {
    font-family: \'roboto\';
    src: url(\'/assets/' . $fontRoboto . ') format(\'woff\');
    font-weight: normal;
    font-style: normal;
}

@font-face {
    font-family: \'roboto\';
    src: url(\'/assets/' . $fontRobotoBold . ') format(\'woff\');
    font-weight: normal;
    font-style: normal;
}
</style>';
}


/**
 * Template for head.
 *
 * @param string $title - A title for your head.
 * @return string - Footer's content, HTML elements
 */
function fetchHeadErrorsPage(string $title): string
{
    return '
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Commpass, le CRM spécialisé dans la gestion des budgets de communication, vous aide à créer des comptes-rendus clients clairs et performants. Simplifiez la gestion de vos campagnes.">
    <title>' . $title . '</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <!-- if development -->
    <!-- <script type="module" src="http://localhost:5173/@vite/client"></script> -->
    <!-- <script type="module" src="http://localhost:5173/js/script.js"></script> -->

    <!-- Production -->
    <link rel="stylesheet" href="../assets/assets/script-CxvkZjnF.css">
    <script type="module" src="../assets/assets/script-CsENuWDK.js"></script>

    <!-- C3 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.css" />

    <!-- D3 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/5.16.0/d3.min.js"></script>

    <!-- C3 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.js"></script>';
}