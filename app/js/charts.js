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

window.onload = function() {
    html2canvas(document.getElementById('chart')).then(function(canvas) {
        var imgData = canvas.toDataURL('img/png');
        var link = document.createElement('a');
        link.href = imgData;
        link.download = 'donut_chart.png';
        link.click();
    });
};