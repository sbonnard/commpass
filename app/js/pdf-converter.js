document.getElementById('generatePDF').addEventListener('click', function(event) {
    event.preventDefault();

    // Récupérer le contenu HTML (données de la campagne)
    const campaignContent = document.querySelector('.container--campaigns').innerHTML;

    // Convertir le graphique C3.js en image base64
    const svg = document.querySelector('#chart svg');

    
    const svgData = new XMLSerializer().serializeToString(svg);
    
    // Créer une image base64 à partir du SVG
    const canvas = document.createElement('canvas');
    // canvas.setAttribute('style', 'background-color: white;');
    const ctx = canvas.getContext('2d');
    const img = new Image();

    const svgBase64 = 'data:image/svg+xml;base64,' + window.btoa(unescape(encodeURIComponent(svgData)));
    img.onload = function() {
        canvas.width = svg.clientWidth;
        canvas.height = svg.clientHeight;
        ctx.drawImage(img, 0, 0);
        const chartImageBase64 = canvas.toDataURL('image/png');

        // Remplir les champs cachés avec les données récupérées
        document.getElementById('htmlContent').value = campaignContent;
        document.getElementById('chartImage').value = chartImageBase64;

        // Soumettre le formulaire
        document.getElementById('formPDF').submit();
    };

    img.src = svgBase64;  // Convertir le SVG en image
});