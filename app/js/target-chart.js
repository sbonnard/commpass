function generateTargetChart() {
    // Vérifier si des données sont disponibles pour générer le graphique
    if (targetChartData.length === 0) {
        var width = window.innerWidth < 768 ? 375 : 450;
        var height = window.innerWidth < 768 ? 250 : 300;

        // Si aucune donnée, on affiche un donut grisé
        var chart = c3.generate({
            bindto: '#chart-target',
            data: {
                columns: [
                    ['En attente d\'opération', 1] // Donut "Aucune donnée"
                ],
                type: 'donut',
                colors: {
                    'En attente d\'opération': '#d3d3d3' // Couleur grise pour "Aucune donnée"
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
        // Générer le graphique avec les données et couleurs
        var chart = c3.generate({
            bindto: '#chart-target',
            data: {
                columns: targetChartData,
                type: 'donut',
                colors: targetChartColors // Appliquer les couleurs des objectifs
            },
            size: {
                width: window.innerWidth < 768 ? 375 : 450,
                height: window.innerWidth < 768 ? 250 : 300
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
}
// Générer les graphiques lorsque la page est chargée
window.onload = generateTargetChart;