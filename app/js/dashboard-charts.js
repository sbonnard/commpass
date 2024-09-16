
    // Fonction pour générer les graphiques
    function generateCharts() {
        // Boucle sur chaque graphique
        document.querySelectorAll('.js-chart').forEach(function(chartElement) {
            var campaignId = chartElement.id.split('-')[1];
            var data = chartData[campaignId] || [];
            var colors = chartColors[campaignId] || {};

            // Génération du graphique
            c3.generate({
                bindto: '#' + chartElement.id,
                data: {
                    columns: data,
                    type: 'donut',
                    colors: colors,
                    onclick: function(d, i) {
                        console.log("onclick", d, i);
                    },
                    onmouseover: function(d, i) {
                        console.log("onmouseover", d, i);
                    },
                    onmouseout: function(d, i) {
                        console.log("onmouseout", d, i);
                    }
                },
                size: {
                    width: 450, // Ajuster la largeur du graphique
                    height: 300 // Ajuster la hauteur si nécessaire
                },
                padding: {
                    right: 20,
                    left: 20
                },
                donut: {
                    title: ""
                }
            });
        });
    }

    // Générer les graphiques lorsque la page est chargée
    window.onload = generateCharts;