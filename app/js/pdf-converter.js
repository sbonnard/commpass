document.getElementById('generatePDF').addEventListener('click', function(event) {
    event.preventDefault();

    // Récupérer le contenu HTML (données de la campagne)
    const campaignContent = document.querySelector('#pdfContent').innerHTML;

    // Convertir le graphique C3.js en image base64
    const svg = document.querySelector('#chart svg');
    const svgData = new XMLSerializer().serializeToString(svg);
    
    // Créer une image base64 à partir du SVG
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    const img = new Image();

    const svgBase64 = 'data:image/svg+xml;base64,' + btoa(encodeURIComponent(svgData).replace(/%([0-9A-F]{2})/g, function(match, p1) {
        return String.fromCharCode(parseInt(p1, 16));
    }));
    
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
