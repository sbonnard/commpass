document.getElementById('generatePDF').addEventListener('click', function (event) {
    event.preventDefault();

    // Récupérer le contenu HTML (données de la campagne)
    const campaignContent = document.querySelector('#pdfContent').innerHTML;

    // Convertir le graphique C3.js en image base64
    const svg = document.querySelector('#chart svg');
    const svgData = new XMLSerializer().serializeToString(svg);
    svg.classList.add('svg-chart');

    // Créer une image base64 à partir du SVG
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');

    const img = new Image();

    const svgBase64 = 'data:image/svg+xml;base64,' + btoa(encodeURIComponent(svgData).replace(/%([0-9A-F]{2})/g, function (match, p1) {
        return String.fromCharCode(parseInt(p1, 16));
    }));

    img.onload = function () {
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


/////////////////////////////////////////////////////////////////////////////////
// LE CODE CI-DESSOUS PERMET DE SUPPRIMER OU CACHER DES ÉLÉMENTS GÊNANTS SUR LE PDF.

const gRects = document.querySelectorAll('.c3-event-rects');
console.log(gRects);

gRects.forEach(gRect => {
    gRect.style.opacity = '1';
    gRect.style.fillOpacity = '1';
    gRect.style.fill = 'white';
});

const legends = document.querySelectorAll('.c3-legend-item-event');
console.log(legends);

legends.forEach(legend => {
    legend.style.opacity = '0';
    legend.style.fillOpacity = '0';
    legend.style.fill = 'white';
    // legend.style.margin = '16px';
});

const c3axis = document.querySelectorAll('.c3-axis');
const c3axisX = document.querySelectorAll('.c3-axis-x');

console.log(c3axis);
console.log(c3axisX);

c3axis.forEach(c3axis => {
    c3axis.style.opacity = '0';
    c3axis.style.display = 'hidden';
    c3axis.style.color = '#FFFFFF';
});

c3axisX.forEach(c3axisX => {
    c3axisX.style.opacity = '0';
    c3axisX.style.display = 'hidden';
    c3axisX.style.color = '#FFFFFF';
});

const ticks = document.querySelectorAll('.tick');
console.log(ticks);


ticks.forEach(tick => {
    tick.remove();
});

const legendsText = document.querySelectorAll('.c3-legend-item');
console.log(legendsText);

legendsText.forEach(legendText => {
    legendText.style.fontSize = '11px';
});


// LE CODE CI-DESSUS PERMET DE SUPPRIMER OU CACHER DES ÉLÉMENTS GÊNANTS SUR LE PDF.
/////////////////////////////////////////////////////////////////////////////////