import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';

pdfMake.vfs = pdfFonts.pdfMake.vfs;

document.getElementById('generatePDF').addEventListener('click', function (event) {
    event.preventDefault();

    // Récupération du contenu HTML de la page
    const campaignContent = document.querySelector('.container--campaigns').innerHTML;
    const chartImage = document.getElementById('chartImage').value;

    // Construction du document PDF avec le contenu récupéré
    const documentDefinition = {
        content: [
            { text: 'Rapport de campagne', style: 'header' },
            { text: new Date().toLocaleDateString(), alignment: 'right' },
            { text: 'Données de la campagne :', style: 'subheader' },
            { text: campaignContent, style: 'htmlContentStyle' },  // Ici tu insères le contenu HTML
            { image: chartImage, width: 500 }  // Graphique en image
        ],
    };

    // Génération du PDF et téléchargement
    pdfMake.createPdf(documentDefinition).download('rapport_campagne.pdf');
});


document.getElementById('generatePDF').addEventListener('click', function (event) {
    event.preventDefault();

    // Récupération du contenu HTML de la campagne
    const campaignContent = document.querySelector('.container--campaigns').innerHTML;
    document.getElementById('htmlContent').value = campaignContent;

    // Optionnel : Capture de l'image du graphique si nécessaire
    const chartImage = document.getElementById('chartImage').value;
    if (chartImage) {
        document.getElementById('chartImage').value = chartImage;
    }

    // Soumission du formulaire pour générer le PDF
    document.getElementById('formPDF').submit();
});