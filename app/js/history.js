// CE SCRIPT AUTOMATISE L'AJOUT DE DATES DANS L'HISTORIQUE

// Récupérer l'élément select
const yearSelect = document.getElementById('year');

// Obtenir l'année actuelle
const currentYear = new Date().getFullYear();

// Ajouter des options pour chaque année de l'année actuelle jusqu'à une limite (par exemple, 2023)
for (let year = currentYear - 1; year >= 2023; year--) {
    const option = document.createElement('option');
    option.value = year; // Valeur de l'option
    option.textContent = year; // Texte affiché
    yearSelect.appendChild(option); // Ajouter l'option au select
}