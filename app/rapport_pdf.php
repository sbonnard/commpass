<?php
require 'vendor/autoload.php';
require_once 'includes/_functions.php';

use Dompdf\Dompdf;
use Dompdf\Options;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (isset($_POST['htmlContent'])) {
    define("DOMPDF_ENABLE_REMOTE", false);
    // Initialiser Dompdf
    // Récupérer le contenu HTML posté depuis le formulaire
    $htmlContent = $_POST['htmlContent'];

    $chartImage = $_POST['chartImage'];

    $dompdf = new Dompdf();
    // Date du jour 
    $today = formatFrenchDate(date('Y-m-d'));

    $logo = '<img src="img/logo-tdc.jpg">';

    $header = '<h1 class="ttl">Toile de Com</h1>
    <h3>Rapport du ' . $today . '</h3>';

    $chart = '<img class="chart-png" src="' . $chartImage . '">';

    $css = "
    <style>
:root {
    font-size: 16px;
}

body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        text-align: center;
}

 h1, h2, h3 {
        color: #DA428F;
}

p {
        font-size: 14px;
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
    position: relative;
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
        left: 30%;
        bottom: 2%;
    }

.vignette--secondary {
        background-color: #842078;
        left: 30%;
        bottom: 1%;
    }

.vignette--tertiary {
        background-color: #DA428F;
        left: 30%;
        bottom: 0%;
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
    </style>
    ";

    // Charge le contenu HTML dans le PDF.
    $dompdf->loadHtml($css . $logo . $header . $htmlContent . $chartImage);

    // Lire les images
    $options = new Options();

    $options->setIsRemoteEnabled(true);

    // Définir la taille et l'orientation du papier
    $dompdf->setPaper('A4', 'paysage');
    // Rendu du PDF
    $dompdf->render();
    // Sortie du PDF dans le navigateur
    $dompdf->stream("document.pdf", ["Attachment" => false]);
}
