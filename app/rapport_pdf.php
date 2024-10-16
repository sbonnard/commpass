<?php
require 'vendor/autoload.php';
require_once 'includes/_functions.php';

// Gestion des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérification des données POST
if (isset($_POST['htmlContent'])) {
    $htmlContent = $_POST['htmlContent'] ?? '';
    $chartImage = $_POST['chartImage'] ?? '';

    // Initialisation de MPDF
    $mpdf = new \Mpdf\Mpdf();

    // Date du jour
    $today = date('Y-m-d');

    // Le logo de Toile de Com
    $logo = '<img class="logo" src="img/logo-tdc.jpg" alt="Logo Toile de Com" style="width:350px;">';

    // Le header avec la date du rapport
    $header = '
        <h3>Rapport du ' . formatFrenchDate($today) . '</h3>
    ';

    // Le graphique en image base64
    $chart = '<img src="' . $chartImage . '" alt="Graphique" style="width:500px;">';

    // Le CSS pour styliser le PDF
    $css = "
<style>
:root {
    font-size: 16px;
}

body {
        font-family: Helvetica, sans-serif;
        margin: 0;
        padding: 20px;
        text-align: center;
}

 h1, h2, h3 {
        color: #DA428F;
}

p {
        font-size: 16px;
        color: #555;
}

.flex-row {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
    width: 100%;
}

.card__section {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #FFF;
    border: 1px solid #DA428F;
    border-radius: 0.75rem;
    gap: 0.625rem;
    padding: 1rem 0.625rem;
    min-width: 20.5rem;
    text-align: left;
}

.card__section--vignettes {
    padding: 1rem 3.125rem;
    justify-content: center;
    align-items: center;
}

.card__section--operations {
    width: 100%;
}

.campaign__company {
    font-family: 'jura';
    font-size: 1.875rem;
}

.campaign__interlocutor {
    font-size: 1.25rem;
}

.campaign__stats {
    display: flex;
    flex-direction: row;
    width: 100%;
    justify-content: space-between;
}

.ttl {
  color: #44277a;
  font-family: 'jura';
  font-size: 1.875rem;
  text-transform: uppercase;
  text-align: center;
}

.ttl--smaller {
    font-size: 1.25rem;
}

.ttl--tertiary {
    color: #DA428F;
}

.vignettes--section {
    display: flex;
    flex-direction: row;
    gap: 1.5rem;
    justify-content: center;
    align-items: center;
    flex-wrap: no-wrap;
    height: 1000px;
    position: relative;
}
    
.vignette {
    text-align: center;
    gap: 0.5rem;
    color: #FFF;
    padding: 0.1rem;
    border-radius: 0.75rem 0.75rem 0.75rem 0;
    height: fit-content;
    min-width: 8rem;
    margin-left: 12rem;
    margin-bottom: 0.5rem;
}

.vignette__ttl {
    font-family: 'jura';
    font-size: 1rem;
    text-transform: uppercase;
}  

.vignette__price {
    font-family: 'jura';
    font-size: 1.5rem;
    color: #FFF;
    text-align: center;
}

.vignette--big {
        width: 14.875rem;
        gap: 1.5rem;
        font-size: 1.25rem;
    }

.vignette--primary {
        background-color: #44277A;
    }

.vignette--secondary {
        background-color: #842078;
    }

.vignette--tertiary {
        background-color: #DA428F;
    }

.vignette--bigger {
        width: 14.875rem;
        gap: 1.5rem;
        font-size: 1.5rem;
}
.vignettes-PDF {
    display: flex;
    justify-content: center;
}
.table {
    background-color: #FFF;
    font-family: 'jura';
    border: 1px solid #44277A;
    border-radius: 0.5rem;
    width: 100%;
    border-collapse: collapse;
    min-width: 27.25rem;
}
.table__head {
    font-size: 1.25rem;
    text-transform: uppercase;
    color: #44277A;
    padding: 0.5rem 1rem;
}
.table__cell {
    padding: 0.5rem 1rem;
    border : 1px solid #44277A;
    text-align: center;
    font-size: 1.25rem;
}

.c3 {
    background-color: #FFF;
    margin-left: 12rem;
}
</style>
";

    // Combiner le contenu HTML avec le CSS
    $html = $css . $logo . $header . $htmlContent;

    // Charger le contenu HTML dans MPDF
    $mpdf->WriteHTML($html);

    // Nom du fichier
    $fileName = "rapport_tdc_$today.pdf";

    // Générer le fichier PDF et l'afficher dans le navigateur
    $mpdf->Output($fileName, 'I'); // 'I' pour afficher, 'D' pour forcer le téléchargement
}
