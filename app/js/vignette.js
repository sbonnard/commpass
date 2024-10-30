// CHANGER LA VIGNETTE EN ROUGE SI LA VALEUR DANS L'ÉLÉMENT EST NÉGATIVE.

document.querySelectorAll('.vignette[data-vignette]').forEach(function(vignette) {
    let priceElement = vignette.querySelector('.vignette__price');
    let value = parseFloat(priceElement.textContent.replace(/[^\d.-]/g, '')); // Supprime les symboles et garde le nombre

    if (value < 0) {
        vignette.classList.add('vignette-negative'); // Ajoute une classe pour les valeurs négatives
    }
});