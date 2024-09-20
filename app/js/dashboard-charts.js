
// Fonction pour générer les graphiques
function generateCharts() {
    // Boucle sur chaque graphique
    document.querySelectorAll('.js-chart').forEach(function (chartElement) {
        var campaignId = chartElement.id.split('-')[1];
        var data = chartData[campaignId] || [];
        var colors = chartColors[campaignId] || {};
        var width = window.innerWidth < 768 ? 375 : 450; // Si l'écran est plus petit que 768px, utiliser 300px sinon 600px
        var height = window.innerWidth < 768 ? 250 : 300; // Hauteur en fonction de la largeur

        if (!data || data.length === 0) {
            // Si aucune donnée, on crée un donut grisé
            c3.generate({
                bindto: '#' + chartElement.id,
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
            // Génération du graphique
            c3.generate({
                bindto: '#' + chartElement.id,
                data: {
                    columns: data,
                    type: 'donut',
                    colors: colors,
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
        }
    });
}

// Générer les graphiques lorsque la page est chargée
window.onload = generateCharts;