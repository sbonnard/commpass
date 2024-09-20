var chartColors = {};
chartData.forEach(function (row) {
    chartColors[row[0]] = row[2];
});

// Génération du graphique

if (!chartData || chartData.length === 0) {
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
            width: 600,
            height: 400
        },
        padding: {
            right: 20,
            left: 20
        },
        donut: {
            title: "Aucune opération effectuée"
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
            width: 600, // Ajuster la largeur du graphique
            height: 400 // Ajuster la hauteur si nécessaire
        },
        padding: {
            right: 20,
            left: 20
        },
        donut: {
            title: ""
        }
    });
}