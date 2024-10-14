<?php
require 'vendor/autoload.php';
require_once 'includes/_functions.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (isset($_POST['htmlContent'])) {
    $pdf = new TCPDF();

    // Ajouter une page
    $pdf->AddPage();

    // Définir la police
    $pdf->SetFont('helvetica', '', 12);

    $pdf->SetMargins(10, 10, 10); // Gauche, Haut, Droite

    // Récupérer le contenu HTML posté depuis le formulaire.
    $htmlContent = $_POST['htmlContent'];

    $today = date('Y-m-d');

    $TDCLogo = '<img class="logo" src="img/logo-tdc.jpg" alt="Logo Toile de Com">';

    $date = '<h3>Compte rendu du ' . formatFrenchDate($today) . '</h3>';

    // $chart = '<img src="https://www.toiledecom.fr/wp-content/uploads/2020/06/Logo-toile-de-com-1.png">';

    $css = "
    <style>
@font-face {
    font-family: 'roboto';
    src: url('scss/fonts/roboto-regular-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
}

@font-face {
    font-family: 'roboto-bold';
    src: url('scss/fonts/roboto-bold-webfont.woff2') format('woff2');
    font-weight: bold;
    font-style: normal;
}

@font-face {
    font-family: 'jura';
    src: url('scss/fonts/jura.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
}

body {
        margin: 0;
        padding: 20px;
    }

 h1, h2, h3 {
        font-family: 'jura';
        color: #DA428F;
        text-transform: uppercase;
        text-align: center;
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
    gap: 8px;
}

.card {
    display: flex;
    flex-direction: column;
}

.card__section {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #FFF;
    border: 1px solid #DA428F;
    border-radius: 12px;
    padding: 16px 10px;
    min-width: 28px;
    text-align: left;
}

.card__section--vignettes {
    padding: 16px 50px;
}

.card__section--operations {
    width: 100%;
}

.campaign__company {
    font-family: 'jura';
    font-size: 20px;
}

.campaign__interlocutor {
    font-size: 16px;
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
  font-size: 24px;
  text-transform: uppercase;
  text-align: center;
}

.ttl--smaller {
    font-size: 18px;
}

.ttl--tertiary {
    color: #DA428F;
}

.vignettes-section {
    gap: 24px;
    justify-content: center;
    align-items: center;
}
    
.vignette {
    text-align: center;
    color: #FFF;
}

.vignette__ttl {
    font-size: 16px;
    text-transform: uppercase;
}  

.vignette__price {
    font-family: 'jura';
    font-size: 20px;
    color: #FFF;
    text-align: center;
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

.vignette--negative {
        background-color: rgba(195, 0, 0, 0.90);
}

.table {
    background-color: #FFF;
    font-family: 'jura';
    border: 2px solid #44277A;
    border-radius: 8px;
    width: 100%;
    border-collapse: collapse;
    min-width: 436px;
    text-align: center;
}

.table__head {
    font-size: 20px;
    text-transform: uppercase;
    color: #44277A;
    padding: 8px 16px;
}

.table__cell {
    padding: 8px 16px;
    border : 1px solid #44277A;
    text-align: center;
    font-size: 16px;
}

.logo {
width: 350px;
}

</style>";

    // Chemin de l'image du graphique
    $chartImage = 'img/donut_chart.png'; // Assurez-vous que ce chemin est correct

    // Ajouter le graphique en donut
    $pdf->Image($chartImage, '', '', 150, 150, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);

    // Combine le CSS et le contenu HTML
    $html = $css . $TDCLogo . $date . $htmlContent;

    // Écrit le HTML dans le PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Sortir le PDF
    $pdf->Output('compte-rendu.pdf', 'I');
}
