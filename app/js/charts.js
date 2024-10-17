var chartColors = {};
chartData.forEach(function (row) {
    chartColors[row[0]] = row[2];
});

// Génération du graphique

if (!chartData || chartData.length === 0) {
    var width = window.innerWidth < 768 ? 375 : 450; // Si l'écran est plus petit que 768px, utiliser 300px sinon 600px
    var height = window.innerWidth < 768 ? 250 : 300; // Hauteur en fonction de la largeur

    // Si aucune donnée, on crée un donut grisé
    var chart = c3.generate({
        bindto: '#chart',
        data: {
            columns: [
                ['En attente d\'opération', 1] // Donut "Aucune donnée"
            ],
            type: 'donut',
            colors: {
                'En attente d\'opération': '#d3d3d3' // Couleur grise pour "Aucune donnée"
            },
            onclick: function (d, i) {
                console.log("onclick", d, i);
            },
            onmouseover: function (d, i) {
                console.log("onmouseover", d, i);
            },
            onmouseout: function (d, i) {
                console.log("onmouseout", d, i);
            }
        },
        size: {
            width: width,
            height: height
        },
        padding: {
            right: 20,
            left: 20
        },
        donut: {
            title: "Aucune opération"
        }
    });
} else {
    var chart = c3.generate({
        bindto: '#chart',
        data: {
            columns: chartData,
            type: 'donut',
            colors: chartColors, // Appliquer la couleur hex des données PHP.
            onclick: function (d, i) {
                console.log("onclick", d, i);
            },
            onmouseover: function (d, i) {
                console.log("onmouseover", d, i);
            },
            onmouseout: function (d, i) {
                console.log("onmouseout", d, i);
            }
        },
        size: {
            width: width, // Ajuster la largeur du graphique
            height: height // Ajuster la hauteur si nécessaire
        },
        padding: {
            right: 20,
            left: 20
        },
        donut: {
            title: ""
        }
    });
};

// // Capture du graphique en tant qu'image
// setTimeout(function () {
//     var chartElement = document.querySelector('#chart svg');  // Sélectionne l'élément SVG du graphique
//     var svgData = new XMLSerializer().serializeToString(chartElement);

//     // Ajouter un rectangle blanc au SVG
//     const rect = '<rect width="100%" height="100%" fill="white" />'; // Rectangle blanc
//     svgData = svgData.replace('<svg', `<svg>${rect}`); // Ajouter le rectangle avant le contenu SVG

//     var canvas = document.createElement('canvas');
//     var ctx = canvas.getContext('2d');
//     var img = new Image();

//     img.onload = function () {
//         canvas.width = chartElement.clientWidth; // Définir la largeur du canvas
//         canvas.height = chartElement.clientHeight; // Définir la hauteur du canvas
//         ctx.drawImage(img, 0, 0);
//         var chartImage = canvas.toDataURL('image/png');
//         document.getElementById('chartImage').value = chartImage;  // Stocke l'image du graphique
//     };
    
//     img.src = 'data:image/svg+xml;base64,' + btoa(svgData); // Convertir le SVG modifié
// }, 1000);

